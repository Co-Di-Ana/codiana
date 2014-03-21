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
 * The main codiana configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_codiana
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined ('MOODLE_INTERNAL') || die();

require_once ($CFG->dirroot . '/course/moodleform_mod.php');
require_once ($CFG->dirroot . '/mod/codiana/locallib.php');

/**
 * Module instance settings form
 */
class mod_codiana_mod_form extends moodleform_mod {

    /** @var MoodleQuickForm */
    private $mform;

    /** @var bool */
    private $isEditing;

    /** @var int */
    private $settingIndex = 0;



    public function codiana_add_instance ($data) {
        echo "add <pre>";
        print_r ($data);
        die ();
    }



    /**
     * Defines forms elements
     */
    public function definition () {
        global $PAGE;

        $PAGE->requires->jquery ();
        $PAGE->requires->js ('/mod/codiana/html/js/mod_form.js', true);


        $this->isEditing = $this->_instance == "";
        $this->mform     = $this->_form;

        // -----------------------------------------------------------------------------
        // ----- GENERAL ---------------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'taskgeneral', get_string ('general', 'form'));
        $this->mform->setExpanded ('taskgeneral');
        {
            // task name
            $this->addTaskNameElement ();

            // task main file name
            $this->addTaskMainFileNameElement ();

            // grade method
            $this->addTaskGradeMethodElement ();

            // output method
            $this->addTaskOutputMethodElement ();

            // allowed languages
            $this->addTaskLanguagesElement ();

            // difficulty element
            $this->addTaskDifficultyElement ();

            // task intro ans introfield
            $this->addTaskIntroElement ();
        }


        // -----------------------------------------------------------------------------
        // ----- AVAILABLITY -----------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'taskavailability', codiana_string ('section:availability'));
        $this->mform->setExpanded ('taskavailability');
        {
            // task available from date
            $this->addTaskRangeFromElement ();

            // task available to date
            $this->addTaskRangeToElement ();
        }



        // -----------------------------------------------------------------------------
        // ----- LIMITS ----------------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'tasklimits', codiana_string ('section:limits'));
        $this->mform->setExpanded ('tasklimits');
        {
            // task available from date
            $this->addTaskMaxUsersElement ();

            // task available to date
            $this->addTaskMaxAttemptsElement ();

            // task time limit
            $this->addTaskLimitTimeElement ();

            // task memory limit
            $this->addTaskLimitMemoryElement ();
        }



        // -----------------------------------------------------------------------------
        // ----- I/O EXAMPLES ----------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'taskexamples', codiana_string ('section:examples'));
        $this->mform->setExpanded ('taskexamples');
        {
            // task input example
            $this->addTaskInputExample ();

            // task output example
            $this->addTaskOutputExample ();
        }

        // -----------------------------------------------------------------------------
        // ----- RESULTS ---------------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'taskresults', codiana_string ('section:results'));
        $this->mform->setExpanded ('taskresults');
        {
            // task setitngs
            $this->addTaskSettings ();
        }

        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements ();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons ();

    }



    private function addTaskNameElement () {
        $this->mform->addElement ('text', 'name', codiana_string ('name'), array ('size' => '64'));
        $this->mform->setType ('name', PARAM_TEXT);
        $this->mform->addRule ('name', null, 'required', null, 'client');
        $this->mform->addRule ('name', null, 'required', null, 'server');
        $this->mform->addRule ('name', get_string ('maximumchars', '', 64), 'maxlength', 64, 'client');
        $this->mform->addHelpButton ('name', 'name', 'codiana');
    }



    private function addTaskMainFileNameElement () {
        $this->mform->addElement ('text', 'mainfilename', codiana_string ('mainfilename'), array ('size' => '64'));
        $this->mform->setType ('mainfilename', PARAM_TEXT);
        $this->mform->addRule ('mainfilename', null, 'required', null, 'client');
        $this->mform->addRule ('mainfilename', null, 'required', null, 'server');
        $this->mform->addRule ('mainfilename', get_string ('maximumchars', '', 64), 'maxlength', 64, 'client');
        $this->mform->addHelpButton ('mainfilename', 'mainfilename', 'codiana');
    }



    private function addTaskIntroElement () {
        $this->add_intro_editor ();
    }



    private function addTaskDifficultyElement () {
        $difficulty = array ('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5);
        $this->mform->addElement ('select', 'difficulty', codiana_string ('difficulty'), $difficulty);
        $this->mform->setType ('difficulty', PARAM_INT);
        $this->mform->getElement ('difficulty')->setSelected ('3');
        $this->mform->addHelpButton ('difficulty', 'difficulty', 'codiana');
    }



    private function addTaskGradeMethodElement () {
        $gradeMethod = codiana_get_strings_from_array (codiana_grade_method::$types);
        $this->mform->addElement ('select', 'grademethod', codiana_string ('grademethod'), $gradeMethod);
        $this->mform->getElement ('grademethod')->setSelected (codiana_grade_method::SOLUTION_LAST);
        $this->mform->addRule ('grademethod', null, 'required', null, 'client');
        $this->mform->addRule ('grademethod', null, 'required', null, 'server');
        $this->mform->setType ('grademethod', PARAM_INT);
        $this->mform->addHelpButton ('grademethod', 'grademethod', 'codiana');
    }



    private function addTaskOutputMethodElement () {
        $outputMethod = codiana_get_strings_from_array (codiana_output_method::$types);
        $this->mform->addElement ('select', 'outputmethod', codiana_string ('outputmethod'), $outputMethod);
        $this->mform->getElement ('outputmethod')->setSelected (codiana_output_method::GRADE_TOLERANT);
        $this->mform->addRule ('outputmethod', null, 'required', null, 'client');
        $this->mform->addRule ('outputmethod', null, 'required', null, 'server');
        $this->mform->setType ('outputmethod', PARAM_INT);
        $this->mform->addHelpButton ('outputmethod', 'outputmethod', 'codiana');
    }



    private function addTaskLanguagesElement () {
        global $DB;
        $result    = $DB->get_records ('codiana_language', null, '', 'name,extension');
        $languages = array ();
        foreach ($result as $language)
            $languages[$language->name] = sprintf ('%s (*.%s)', $language->name, $language->extension);

        reset ($languages);
        $selected = key ($languages);

        $this->mform->addElement ('select', 'languages', codiana_string ('languages'), $languages);
        $this->mform->getElement ('languages')->setSelected ($selected);
        $this->mform->getElement ('languages')->setMultiple (true);
        $this->mform->addRule ('languages', null, 'required', null, 'client');
        $this->mform->addRule ('languages', null, 'required', null, 'server');
        $this->mform->setType ('languages', PARAM_ALPHANUMEXT);
        $this->mform->addHelpButton ('languages', 'languages', 'codiana');
    }



    private function addTaskRangeFromElement () {
        $options = array (
            'startyear' => 2013,
            'stopyear'  => 2014,
            'timezone'  => 99,
            'step'      => 5,
            'optional'  => true
        );
        //TODO availablefromdate get_string ();!
        $this->mform->addElement ('date_time_selector', 'timeopen', codiana_string ('timeopen'), $options);
        $this->mform->setDefault ('timeopen', time ());
        $this->mform->addHelpButton ('timeopen', 'timeopen', 'codiana');
    }



    // TODO date check
    private function addTaskRangeToElement () {
        $options = array (
            'startyear' => 2013,
            'stopyear'  => 2014,
            'timezone'  => 99,
            'step'      => 5,
            'optional'  => true
        );
        $this->mform->addElement ('date_time_selector', 'timeclose', codiana_string ('timeclose'), $options);
        $this->mform->setDefault ('timeclose', time () + 60 * 60 * 24 * 7);
        $this->mform->addHelpButton ('timeclose', 'timeclose', 'codiana');
    }



    private function addTaskMaxUsersElement () {
        $maxUsersElement =& $this->mform->createElement ('text', 'maxusers', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'maxusers_enabled', '', get_string ('enable'));


        $this->mform->setDefault ('maxusers', 15);
        $this->mform->setDefault ('maxusers_enabled', 1);

        $items = array ($maxUsersElement, $checkboxElement);
        $this->mform->addGroup ($items, 'maxusers_group', codiana_string ('maxusers'), ' ', false);
        $this->mform->disabledIf ('maxusers_group', 'maxusers_enabled');
        $this->mform->setType ('maxusers', PARAM_INT);
        $this->mform->addHelpButton ('maxusers_group', 'maxusers', 'codiana');
    }



    private function addTaskMaxAttemptsElement () {
        $maxAttemptsElement =& $this->mform->createElement ('text', 'maxattempts', '');
        $checkboxElement    =& $this->mform->createElement ('checkbox', 'maxattempts_enabled', '', get_string ('enable'));

        $this->mform->setDefault ('maxattempts', 10);
        $this->mform->setDefault ('maxattempts_enabled', 1);

        $items = array ($maxAttemptsElement, $checkboxElement);
        $this->mform->addGroup ($items, 'maxattempts_group', codiana_string ('maxattempts'), ' ', false);
        $this->mform->disabledIf ('maxattempts_group', 'maxattempts_enabled');
        $this->mform->setType ('maxattempts', PARAM_INT);
        $this->mform->addHelpButton ('maxattempts_group', 'maxattempts', 'codiana');
    }



    private function addTaskLimitTimeElement () {
        $limitTimeFalling =& $this->mform->createElement ('text', 'limittimefalling', '');
        $limitTimeNothing =& $this->mform->createElement ('text', 'limittimenothing', '');
        $checkboxElement  =& $this->mform->createElement ('checkbox', 'limittime_enabled', '', get_string ('enable'));

        $this->mform->setDefault ('limittimefalling', 3000);
        $this->mform->setDefault ('limittimenothing', 6000);
        $this->mform->setDefault ('limittime_enabled', 1);

        $items = array ($limitTimeFalling, $limitTimeNothing, $checkboxElement);
        $this->mform->addGroup ($items, 'limittime_group', codiana_string ('limittime'), ' ', false);
        $this->mform->disabledIf ('limittime_group', 'limittime_enabled');
        $this->mform->setType ('limittimefalling', PARAM_INT);
        $this->mform->setType ('limittimenothing', PARAM_INT);
        $this->mform->addHelpButton ('limittime_group', 'limittime', 'codiana');
    }



    private function addTaskLimitMemoryElement () {
        $limitMemoryFalling =& $this->mform->createElement ('text', 'limitmemoryfalling', '');
        $limitMemoryNothing =& $this->mform->createElement ('text', 'limitmemorynothing', '');
        $checkboxElement    =& $this->mform->createElement ('checkbox', 'limitmemory_enabled', '', get_string ('enable'));

        $this->mform->setDefault ('limitmemoryfalling', 100);
        $this->mform->setDefault ('limitmemorynothing', 200);
        $this->mform->setDefault ('limitmemory_enabled', 1);

        $items = array ($limitMemoryFalling, $limitMemoryNothing, $checkboxElement);
        $this->mform->addGroup ($items, 'limitmemory_group', codiana_string ('limitmemory'), ' ', false);
        $this->mform->disabledIf ('limitmemory_group', 'limitmemory_enabled');
        $this->mform->setType ('limitmemoryfalling', PARAM_INT);
        $this->mform->setType ('limitmemorynothing', PARAM_INT);
        $this->mform->addHelpButton ('limitmemory_group', 'limitmemory', 'codiana');
    }



    private function addTaskInputExample () {
        $this->mform->addElement ('textarea', 'inputexample', codiana_string ('inputexample'), 'wrap="virtual" rows="10" cols="100" class="codiana_monospaced"');
        $this->mform->setType ('inputexample', PARAM_TEXT);
        $this->mform->addHelpButton ('inputexample', 'inputexample', 'codiana');
    }



    private function addTaskOutputExample () {
        $this->mform->addElement ('textarea', 'outputexample', codiana_string ('outputexample'), 'wrap="virtual" rows="10" cols="100" class="codiana_monospaced"');
        $this->mform->setType ('outputexample', PARAM_TEXT);
        $this->mform->addHelpButton ('outputexample', 'outputexample', 'codiana');
    }



    private function addTaskSettings () {
        $this->settingIndex = 0;
        $this->addTaskSettingBlock ('opensolver', codiana_display_options::OPEN_SOLVER, array ('basestat', 'state'));
        $this->addTaskSettingBlock ('closesolver', codiana_display_options::CLOSE_SOLVER, array ('basestat', 'state'));
        $this->addTaskSettingBlock ('openothers', codiana_display_options::OPEN_OTHERS);
        $this->addTaskSettingBlock ('closeothers', codiana_display_options::CLOSE_OTHERS);
    }



    private function addTaskSettingBlock ($name, $shift, $disabled = array ()) {
        $group   = array ();
        $i       = 0;
        $elem    = null;
        $alwasOn = false;
        foreach (codiana_display_options::$fields as $field) {
            $alwasOn = in_array ($field, $disabled);
            $group[] = $elem = $this->mform->createElement (
                'advcheckbox',
                'setting' . "[$this->settingIndex]", '', $field,
                array ('group' => $shift),
                array (0, 1 << ($i + $shift)));
            $i += codiana_display_options::COUNT;

            // dummy disabled
            if ($alwasOn)
                $this->mform->disabledIf ('setting' . "[$this->settingIndex]", 'difficulty', 'neq', 0);
            $this->settingIndex++;
        }
        $this->mform->addGroup ($group, $name, $name, '<br />', false);
        $this->add_checkbox_controller ($shift);
    }



    function validation ($data, $files) {
        global $CFG;
        require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
        $errors = parent::validation ($data, $files);

        $codiana = (object)$data;


        $tmp = trim (@$codiana->name);
        if (empty($tmp))
            $errors['name'] = 'empty';


        $tmp = trim (@$codiana->mainfilename);
        if (empty($tmp))
            $errors['mainfilename'] = 'empty';
        else if (preg_match ('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tmp) != 1)
            $errors['mainfilename'] = 'regexp';


        if (in_array (@$codiana->grademethod, array_keys (codiana_grade_method::$types)) == false)
            $errors['grademethod'] = 'invalid value';

        if (in_array (@$codiana->outputmethod, array_keys (codiana_output_method::$types)) == false)
            $errors['outputmethod'] = 'invalid value';


        if (!is_array (@$codiana->languages))
            $errors['languages'] = 'not array';
        else {
            global $DB;
            $languages = $DB->get_records ('codiana_language', null, '', 'name');
            $languages = array_keys ($languages);
            foreach ($codiana->languages as $language) {
                if (in_array ($language, $languages) == false)
                    $errors['languages'] = 'Unsupported programming language ' . $language;
            }
        }

        if (in_array (@$codiana->difficulty, array (1, 2, 3, 4, 5)) == false)
            $errors['difficulty'] = 'invalid value';


        if (intval (@$codiana->introeditor['format']) == 0)
            $errors['introformat'] = 'not number';

        $timeopen  = isset ($codiana->timeopen) && $codiana->timeopen != 0
            ? intval (@$codiana->timeopen) : null;
        $timeclose = isset ($codiana->timeclose) && $codiana->timeclose != 0
            ? intval (@$codiana->timeclose) : null;
        if ((!is_null ($timeopen) && !is_null ($timeclose)) && $timeopen >= $timeclose) {
            $errors['timeopen']  = 'dates are colliding';
            $errors['timeclose'] = 'dates are colliding';
        }

        if (!codiana_check_int ($codiana, 'maxusers', false))
            $errors['maxusers_group'] = 'must be non zero integer';

        if (!codiana_check_int ($codiana, 'maxattempts', false))
            $errors['maxattempts_group'] = 'must be non zero integer';

        $a = codiana_check_int ($codiana, 'limittimefalling', false);
        $b = codiana_check_int ($codiana, 'limittimenothing', false);
        if (!$a || !$b)
            $errors['limittime_group'] = 'must be non zero integer';
        else if (@$codiana->limittimenothing < @$codiana->limittimefalling)
            $errors['limittime_group'] = 'zero point value must be smaller than 100% point value';


        $a = codiana_check_int ($codiana, 'limitmemoryfalling', false);
        $b = codiana_check_int ($codiana, 'limitmemorynothing', false);
        if (!$a || !$b)
            $errors['limitmemory_group'] = 'must be non zero integer';
        else if (@$codiana->limitmemorynothing < @$codiana->limitmemoryfalling)
            $errors['limitmemory_group'] = 'zero point value must be smaller than 100% point value';


        $codiana->solutionfile = @$codiana->solutionfile;
        $codiana->inputfile    = @$codiana->inputfile;
        $codiana->outputfile   = @$codiana->outputfile;
        $codiana->errorfile    = @$codiana->errorfile;

        $codiana->visible    = @$codiana->visible;
        $codiana->cmidnumber = @$codiana->cmidnumber;
        $codiana->groupmode  = @$codiana->groupmode;

        if (!is_array (@$codiana->setting)) {

            $errors['opensolver']  = 'invalid data';
            $errors['closesolver'] = 'invalid data';
            $errors['openothers']  = 'invalid data';
            $errors['closeothers'] = 'invalid data';
        }


        return $errors;
    }



    /**
     * Filling form
     * @param array $toform data from DB
     */
    public
    function data_preprocessing (&$toform) {
        parent::data_preprocessing ($toform);


        // fill languages
        if (isset ($toform['languages'])) {
            //TODO secure language values
            $value               = trim ($toform['languages']);
            $toform['languages'] = explode (',', $value);
        }

        // set default values
        if (!isset ($toform['settings']))
            $toform['settings'] = get_config ('codiana', 'setting');

        // fill setting
        if (isset ($toform['settings'])) {
            $settings          = intval ($toform['settings']);
            $toform['setting'] = array ();

            $j = 0;
            foreach (codiana_display_options::$types as $shift) {
                $i = 0;
                foreach (codiana_display_options::$fields as $field) {
                    $toform['setting'][$j++] = intval ($settings & (1 << ($i + $shift)));
                    $i += codiana_display_options::COUNT;
                }
            }
        }

//        echo '<pre>';
//        print_r ($toform);
//        die ();
        // enabled fields
        $checkFields = array (
            'maxusers', 'maxattempts',
            'timeopen', 'timeclose');
        foreach ($checkFields as $field) {
            if (is_null (@$toform[$field])) {
                $toform[$field . '_enabled'] = 0;
            } else {
                $toform[$field . '_enabled'] = @intval ($toform[$field]) > 0;
            }
        }
        $limits = array ('limittime', 'limitmemory');
        foreach ($limits as $field) {
            if (is_null (@$toform[$field . 'falling']) || is_null (@$toform[$field . 'nothing'])) {
                $toform[$field . '_enabled'] = 0;
            } else {
                $toform[$field . '_enabled'] = @intval (@$toform[$field . 'falling']) > 0 &&
                                                @intval (@$toform[$field . 'nothing']) > 0;
            }
        }
    }
}
