/**
 * Created by Jan on 2.11.13.
 */

function toCamelCase(str) {
    return str.replace(/(?:^|\s)\w/g,function (match) {
        return match.toUpperCase();
    }).replace(/\s+/g, "");
}

function replaceDiacritics(s) {
    var s;

    var diacritics = [
        /[\300-\306]/g, /[\340-\346]/g, // A, a
        /[\310-\313]/g, /[\350-\353]/g, // E, e
        /[\314-\317]/g, /[\354-\357]/g, // I, i
        /[\322-\330]/g, /[\362-\370]/g, // O, o
        /[\331-\334]/g, /[\371-\374]/g,  // U, u
        /[\321]/g, /[\361]/g, // N, n
        /[\307]/g, /[\347]/g, // C, c
        'š', 'č', 'ř', 'ž', 'ý', 'ů', 'ú', 'ě',
        'Š', 'Č', 'Ř', 'Ž', 'Y', 'Ů', 'Ú', 'Ě'
    ];

    var chars = [
        'A', 'a',
        'E', 'e',
        'I', 'i',
        'O', 'o',
        'U', 'u',
        'N', 'n',
        'C', 'c',
        's', 'c', 'r', 'z', 'y', 'u', 'u', 'e',
        'S', 'C', 'R', 'Z', 'Y', 'U', 'U', 'E'];

    for (var i = 0; i < diacritics.length; i++)
        s = s.replace(diacritics[i], chars[i]);

    return s;
}

$(function () {
    $('#id_name').bind("input", function (event, data) {
        var taskmainfilename = $('#id_taskmainfilename');
        var form = $(event.target);
        var value = toCamelCase(replaceDiacritics(form.val())).replace(/[^a-zA-Z0-9_]+/g, "");

        taskmainfilename.val(value);
    });
});
