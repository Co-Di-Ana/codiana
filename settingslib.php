<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Multiple checkboxes, value stored as string, optional shift value
 * can be stored as 00 01 or 00 02 or00 04 or 00 08 (two places defines one property)
 * numerical value is defined by shift value
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_configmulticheckbox_base extends admin_setting_configmulticheckbox {

    /** @var mixed */
    private $setting;

    /** @var int */
    private $step = codiana_display_options::COUNT;

    /** @var int */
    private $shift;



    /**
     * Constructor: uses parent::__construct
     *
     * @param string $name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param array $defaultsetting array of selected
     * @param array $choices array of $value=>$label for each checkbox
     * @param int $shift
     */
    public function __construct ($name, $visiblename, $description, $defaultsetting, $choices, $shift) {
        $this->shift = intval ($shift);
        parent::__construct ($name, $visiblename, $description, $defaultsetting, $choices);
    }



    public function setSetting ($value) {
        $this->setting = $value;
    }



    /**
     * @param $data array
     * @return int
     */
    public function getSetting ($data) {
        if (!is_array ($data)) {
            return 0; // ignore it
        }
        if (!$this->load_choices () or empty($this->choices)) {
            return 0;
        }
        $result = 0;
        $i = $this->shift;
        foreach ($this->choices as $key => $unused) {
            if (!empty($data[$key]))
                $result += 1 << $i;
            $i += $this->step;
        }
        return $result;
    }



    /**
     * Returns the setting if set
     *
     * @return int|null if not set, else an int of set settings
     */
    public function get_setting () {
        // get number
        $result = intval ($this->setting);
        if (is_null ($result)) {
            return NULL;
        }
        if (!$this->load_choices ()) {
            return NULL;
        }

        $setting = array ();
        $i = $this->shift;
        foreach ($this->choices as $key => $unused) {
            if ($result & (1 << $i))
                $setting[$key] = 0;
            $i += $this->step;
        }
        return $setting;
    }



    /**
     * Save setting(s) provided in $data param
     *
     * @param array $data An array of settings to save
     * @return mixed empty string for bad data or bool true=>success, false=>error
     */
    public function write_setting ($data) {
        $this->getSetting ($data);
        return true;
    }



    /**
     * Returns XHTML field(s) as required by choices
     *
     * Relies on data being an array should data ever be another valid vartype with
     * acceptable value this may cause a warning/error
     * if (!is_array($data)) would fix the problem
     *
     * @todo Add vartype handling to ensure $data is an array
     *
     * @param array $data An array of checked values
     * @param string $query
     * @return string XHTML field
     */
    public function output_html ($data, $query = '') {
        if (!$this->load_choices () or empty($this->choices)) {
            return '';
        }
        $default = $this->get_defaultsetting ();
        if (is_null ($default)) {
            $default = array ();
        }
        if (is_null ($data)) {
            $data = array ();
        }
        $options = array ();
        $defaults = array ();

        $result = intval ($data);

        $i = $this->shift;
        foreach ($this->choices as $key => $description) {
            if ($result & (1 << $i)) {
                $checked = 'checked="checked"';
            } else {
                $checked = '';
            }
            if (!empty($default[$key])) {
                $defaults[] = $description;
            }

            $options[] = '<input type="checkbox" id="' . $this->get_id () . '_' . $key . '" name="' . $this->get_full_name () . '[' . $key . ']" value="1" ' . $checked . ' />'
                . '<label for="' . $this->get_id () . '_' . $key . '">' . highlightfast ($query, $description) . '</label>';
            $i += $this->step;
        }

        if (is_null ($default)) {
            $defaultinfo = NULL;
        } else if (!empty($defaults)) {
            $defaultinfo = implode (', ', $defaults);
        } else {
            $defaultinfo = get_string ('none');
        }

        $return = '<div class="form-multicheckbox">';
        $return .= '<input type="hidden" name="' . $this->get_full_name () . '[xxxxx]" value="1" />'; // something must be submitted even if nothing selected
        if ($options) {
            $return .= '<ul>';
            foreach ($options as $option) {
                $return .= '<li>' . $option . '</li>';
            }
            $return .= '</ul>';
        }
        $return .= '</div>';

        return format_admin_setting ($this, $this->visiblename, $return, $this->description, false, '', $defaultinfo, $query);

    }
}



/**
 * Class admin_setting_configmulticheckbox_base_group
 * Admin setting containing multiple admin_setting_configmulticheckbox_base
 */
class admin_setting_configmulticheckbox_base_group extends admin_setting {

    /** @var array */
    private $items = array ();

    /** @var array */
    private $choices;

    /** @var int */
    private $index = 0;

    /** @var string */
    private $rawName;

    /** @var mixed */
    private $defaultSetting;



    public function __construct ($name, $visiblename, $description, $defaultsetting, $choices, $step = codiana_display_options::COUNT) {
        $this->choices = $choices;
        $this->rawName = $name;
        $this->defaultSetting = $defaultsetting;
        $this->step = $step;
        parent::__construct ($name, $visiblename, $description, $defaultsetting);
    }



    /**
     * Method adds another setting to setting group
     * @param $visiblename
     * @param $description
     * @param $shift int
     * @return $this
     */
    public function add ($visiblename, $description, $shift) {
        $choices = array ();

        // recreate choices array but with shifted indexes
        // defaults is array where key is index and value is whether is selected or not
        $i = $shift;
        $j = 0;
        $defaults = array ();
        foreach ($this->choices as $value) {
            $choices[$this->index++] = $value;
            if ($this->defaultSetting & (1 << $i))
                $defaults[$this->index - 1] = 1;
            $i += $this->step;
            $j += 1;
        }
        $this->items[] = new admin_setting_configmulticheckbox_base (
            $this->rawName, $visiblename, $description, $defaults, $choices, $shift
        );

        return $this;
    }



    /**
     * Returns current value of this setting
     * @return mixed array or string depending on instance, NULL means not set yet
     */
    public function get_setting () {
        $setting = $this->config_read ($this->name);
        if (is_null ($setting))
            return NULL;

        $setting = intval ($setting);
        foreach ($this->items as $item) {
            $item->setSetting ($setting);
            $item->get_setting ();
        }
        return $setting;
    }



    /**
     * Store new setting
     *
     * @param mixed $data string or array, must not be NULL
     * @return string empty string if ok, string error message otherwise
     */
    public function write_setting ($data) {
        $setting = 0;
        foreach ($this->items as $item)
            $setting += intval ($item->getSetting ($data));

        return $this->config_write ($this->name, $setting) ? '' : get_string ('errorsetting', 'admin');
    }



    /**
     * Returns XHTML field(s) as required by choices
     *
     * @param array $data An array of checked values
     * @param string $query
     * @return string XHTML field
     */
    public function output_html ($data, $query = '') {
        $output = html_writer::tag ('h1', $this->get_setting ());
        foreach ($this->items as $item)
            $output .= $item->output_html ($data, $query);

        return $output;
    }
}