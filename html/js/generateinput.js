$ (function () {

    document.selectItem = function (uid) {
        selectedItem = itemsMap[uid];
        if (!selectedItem)
            return;

        activateSection (selectedItem.core.type, true);
        fillForm (selectedItem);

        if (selectedItem.core.type == 'section')
            selectedSection = selectedItem;

        $ ('#new-element input:radio[value!=' + selectedItem.core.type + ']').prop ('checked', false);
        $ ('#new-element input:radio[value=' + selectedItem.core.type + ']').prop ('checked', true);
        update ();
    }

    var AbstractGenerator = function (parent, type, id, format) {

        /** @type {object} */
        this.parent = parent;

        /** @type {string} */
        this.id = id;

        /** @type {string} */
        this.type = type;

        /** @type {GeneratorSection} */
        this.list = null;

        /** @type {Number} */
        this.uid = generatorID++;
        itemsMap[this.uid.toString ()] = parent;

        /** @type {string} */
        this.format = !isNaN (parseInt (format)) ? '%0' + format + 'd' : format;

        /**
         * @returns {string}
         */
        this.value = function (output) {
            output.push (sprintf (this.format, this.parent.getNext ()));
        }

        /**
         * @param output {Array}
         */
        this.html = function (output) {
            if (this.core.id == undefined || this.core.id == null || this.core.id.length == 0)
                this.core.id = 'item';
            output.push (createLink (this.core.uid, '[' + this.core.id + ']'));
        }
    }

    var GeneratorVariableRandom = function (id, min, max, format) {

        /** @type {AbstractGenerator} */
        this.core = new AbstractGenerator (this, 'variable-random', id, format);

        /** @type {Number} */
        this.min = ~~min;

        /** @type {Number} */
        this.max = ~~max;

        /**
         * @returns {Number}
         */
        this.getNext = function () {
            return ~~((random () * (this.max - this.min + 1)) + this.min);
        }

        this.html = this.core.html;
    }

    var GeneratorVariableStep = function (id, start, step, format) {

        /** @type {AbstractGenerator} */
        this.core = new AbstractGenerator (this, 'variable-step', id, format);

        /** @type {Number} */
        this.start = ~~start;

        /** @type {Number} */
        this.step = ~~step;

        /** @type {Number} */
        this.counter = this.start;

        /**
         * Resets counter to start value
         */
        this.reset = function () {
            this.counter = this.start;
        };

        /**
         * @returns {Number}
         */
        this.getNext = function () {
            var result = this.counter;
            this.counter += this.step;
            return result;
        }

        this.html = this.core.html;
    }

    var GeneratorString = function (id) {

        /** @type {AbstractGenerator} */
        this.core = new AbstractGenerator (this, 'string', id, '%s');


        /**
         * @returns {string}
         */
        this.getNext = function () {
            return this.core.id;
        }

        this.html = this.core.html;
    }

    var GeneratorNewLine = function () {

        /** @type {AbstractGenerator} */
        this.core = new AbstractGenerator (this, 'new-line', '\n', '%s');

        /**
         * @returns {string}
         */
        this.getNext = function () {
            return '\n';
        }

        /**
         * @returns {Array}
         */
        this.html = function (output) {
            output.push (createLink (this.core.uid, '[\\n]'));
        }
    }

    var GeneratorSection = function (id, count, items) {

        /** @type {AbstractGenerator} */
        this.core = new AbstractGenerator (this, 'section', id, '%s');

        /** @type {Array} */
        this.items = items;

        /** @type {Number} */
        this.count = ~~count;

        /**
         * @returns {string}
         */
        this.getNext = function (output) {
            for (var i = 0, l = this.items.length; i < l; i++)
                if (this.items[i].hasOwnProperty ('reset'))
                    this.items[i].reset ();

            for (var j = 0; j < this.count; j++) {
                for (var i = 0, l = Math.min (this.items.length, 10); i < l; i++) {
                    if (this.items[i].core.type == 'section') {
                        if (this.items[i].getNext (output) == false) {
                            return false;
                        }
                    } else {
                        this.items[i].core.value (output);
                    }
                    if (output.length > 256) {
                        output.push ('...');
                        return false;
                    }
                }
            }
            return true;
        }

        /**
         * @param item
         * @returns {Object}
         */
        this.add = function (item) {
            item.core.list = this;
            return this.items.push (item);
        }

        /**
         *
         * @param item
         */
        this.remove = function (item) {
            if (~this.items.indexOf (item))
                this.items.splice (this.items.indexOf (item), 1);
        }

        /**
         * @returns {Array}
         */
        this.html = function (output) {
            output.push (LI_START);
            if (this.items.length == 0)
                output.push (createLink (this.core.uid, '[' + this.core.id + ']'));
            else
                output.push (createLink (this.core.uid, '[' + this.core.id + ' '));
            output.push (LI_END);

            if (this.items.length == 0)
                return output;

            output.push (UL_START);
            var needStart = true;
            var needEnd = false;
            var item;

            for (var i = 0, l = this.items.length; i < l; i++) {
                item = this.items[i];
                if (needStart) {
                    if (item.core.type != 'section')
                        output.push (LI_START);
                    needStart = false;
                    needEnd = true;
                }

                if (item.core.type == 'new-line') {
                    item.html (output);
                    output.push (LI_END);
                    needStart = true;
                    needEnd = false;
                } else if (item.core.type == 'section') {
                    item.html (output);
                    needEnd = false;
                } else {
                    item.html (output);
                }
            }
            if (needEnd)
                output.push (LI_END);
            output.push (UL_END);
            output.push (LI_START);
            output.push ('<span>]</span>');
            output.push (LI_END);
            return output;
        }

        /**
         * @returns {string}
         */
        this.getString = function () {
            var output = [];
            output.push (UL_START);
            this.html (output);
            output.push (UL_END);
            return output.join ('');
            //.concat ('\n\n\n' + output.join ('').replace (/<(\/?)(li|ul)>/gm, '[$1$2]'));
        }

        /**
         * @returns {string}
         */
        this.getValues = function () {
            var output = [];
            this.getNext (output);
            return output.join ('');
        }
    }


    var getValue = function (section, name) {
        return $ ("#edit-" + section + " input[name=" + name + "]").val ();
    }

    var setValue = function (section, name, value) {
        $ ("#edit-" + section + " input[name=" + name + "]").val (value);
    }

    var createLink = function (uid, label) {
        var cls = (selectedSection == itemsMap[uid] ? 'class="selectedSection"' : (
            selectedItem == itemsMap[uid] ? 'class="selectedItem"' : ''));
        return '<a href="#"  onclick="javascript:selectItem(' + uid + '); return false;" ' + cls + '>' + label + '</a>';
    }

    var getGenerator = function (id) {
        switch (id) {
            case 'variable-random':
                return new GeneratorVariableRandom (
                    getValue (id, 'id'), getValue (id, 'min'), getValue (id, 'max'), getValue (id, 'format')
                );
            case 'variable-step':
                return new GeneratorVariableStep (
                    getValue (id, 'id'), getValue (id, 'start'), getValue (id, 'step'), getValue (id, 'format')
                );
            case 'string':
                return new GeneratorString (
                    getValue (id, 'id')
                );
            case 'section':
                return new GeneratorSection (
                    getValue (id, 'id'), getValue (id, 'count'), []
                );
            case 'new-line':
                return new GeneratorNewLine ();
            default:
                return null;
        }
    }

// activate edit section
    var activateSection = function (name, editMode) {
        $ ('form[name|=edit]').hide ();
        $ ('#edit-' + name).show ();
        if (editMode == true) {
            $ ('#edit-' + name + ' button[name=generator-delete-element]').show ();
            $ ('#edit-' + name + ' button[name=generator-edit-element]').show ();
            $ ('#edit-' + name + ' button[name=generator-copy-element]').show ();
            $ ('#edit-' + name + ' button[name=generator-new-element]').hide ();
        } else {
            $ ('#edit-' + name + ' button[name=generator-delete-element]').hide ();
            $ ('#edit-' + name + ' button[name=generator-edit-element]').hide ();
            $ ('#edit-' + name + ' button[name=generator-copy-element]').hide ();
            $ ('#edit-' + name + ' button[name=generator-new-element]').show ();
        }
        currentSection = name;
    }


    var update = function () {
        resetRandom ();

        // grab input and html format
        $ ('#generator-items').html (mainSection.getString ());
        var values = mainSection.getValues ();
        values = '<li>' + values.replace (/\n/gm, '</li><li>').replace (/<li>$/gm, '');
        $ ('#generator-value').html (values);

        // set json to hidden field
        $ ('#id_generateinput').val (JSON.stringify (mainSection, censor));
    }

    var fillForm = function (item) {
        id = item.core.type;
        switch (id) {
            case 'variable-random':
                setValue (id, 'id', item.core.id);
                setValue (id, 'min', item.min);
                setValue (id, 'max', item.max);
                setValue (id, 'format', item.core.format);
                break;
            case 'variable-step':
                setValue (id, 'id', item.core.id);
                setValue (id, 'start', item.start);
                setValue (id, 'step', item.step);
                setValue (id, 'format', item.core.format);
                break;
            case 'string':
                setValue (id, 'id', item.core.id);
                break;
            case 'section':
                setValue (id, 'id', item.core.id);
                setValue (id, 'count', item.count);
                break;
            case 'new-line':
            default:
                break;
        }
    }

    var editItem = function (item) {
        id = item.core.type;
        switch (id) {
            case 'variable-random':
                item.core.id = getValue (id, 'id');
                item.min = ~~getValue (id, 'min');
                item.max = ~~getValue (id, 'max');
                item.core.format = getValue (id, 'format');
                break;
            case 'variable-step':
                item.core.id = getValue (id, 'id');
                item.start = ~~getValue (id, 'start');
                item.step = ~~getValue (id, 'step');
                item.core.format = getValue (id, 'format');
                break;
            case 'string':
                item.core.id = getValue (id, 'id');
                break;
            case 'section':
                item.core.id = getValue (id, 'id');
                item.count = ~~getValue (id, 'count');
                break;
            case 'new-line':
            default:
                break;
        }
    }

    var generatorID = 0;
    var itemsMap = {};
    var selectedItem = null;
    var currentSection;
    var mainSection = new GeneratorSection ('INPUT', 10, []);
    var selectedSection = mainSection;
    var LI_START = '<li>';
    var LI_END = '</li>';
    var UL_START = '<ul>';
    var UL_END = '</ul>';

    var m_w = 123456789;
    var m_z = 987654321;
    var mask = 0xffffffff;

    var resetRandom = function () {
        m_w = 123456789;
        m_z = 987654321;
    }

    var random = function () {
        m_z = (36969 * (m_z & 65535) + (m_z >> 16)) & mask;
        m_w = (18000 * (m_w & 65535) + (m_w >> 16)) & mask;
        var result = ((m_z << 16) + m_w) & mask;
        result /= 4294967296;
        return result + 0.5;
    }

    // radio buttons change
    $ ('#new-element input:radio').change (function (e) {
        activateSection ($ (e.target).val (), false);
    });

    $ ('button[name=generator-new-element], button[name=generator-copy-element]').click (function (e) {
        var item = getGenerator (currentSection);
        if (item != null && selectedSection)
            selectedSection.add (item);
        update ();
    });

    $ ('button[name=generator-edit-element]').click (function (e) {
        if (!selectedItem)
            return;
        activateSection (selectedItem.core.type, true);
        editItem (selectedItem);
        update ();
    });

    $ ('button[name=generator-delete-element]').click (function (e) {
        if (selectedItem && selectedItem.core.list)
            selectedItem.core.list.remove (selectedItem);
        update ();
    });

    mainSection.count = 1;
    var initSection = new GeneratorSection ('inicializace', 4, []);
    mainSection.add (initSection);
    mainSection.add (new GeneratorNewLine ());
    var compSection = new GeneratorSection ('priklady', 10, []);
    mainSection.add (compSection);

    initSection.add (new GeneratorVariableStep ('identifikator', 1, 1, '%02d'));
    initSection.add (new GeneratorString (' '));
    initSection.add (new GeneratorVariableRandom ('hodiny', 0, 23, '%02d'));
    initSection.add (new GeneratorString (':'));
    initSection.add (new GeneratorVariableRandom ('minuty', 0, 59, '%02d'));
    initSection.add (new GeneratorNewLine ());

    compSection.add (new GeneratorVariableRandom ('cas-A-id', 1, 4, '%02d'));
    compSection.add (new GeneratorString (' + '));
    compSection.add (new GeneratorVariableRandom ('cas-B-id', 1, 4, '%02d'));
    compSection.add (new GeneratorNewLine ());

    // initial selection
    $ ('#new-element input:radio').first ().prop ('checked', true).trigger ('change');

    var censor = function (key, value) {
        var censored = ['parent', 'list', 'uid'];
        if (~censored.indexOf (key))
            return undefined;
        return value;
    }

    update ();
});
