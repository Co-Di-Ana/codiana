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

/**
 * Module instance settings form
 */
class mod_codiana_mod_form extends moodleform_mod {

    /** @var MoodleQuickForm */
    private $mform;

    private $isEditing;



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
        $this->mform = $this->_form;

        // -----------------------------------------------------------------------------
        // ----- GENERAL ---------------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'taskgeneral', get_string ('general', 'form'));
        {
            // task name
            $this->addTaskNameElement ();

            // task main file name
            $this->addTaskMainFileNameElement ();

            // grade method
            $this->addTaskGradeMethodElement ();

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
        $this->mform->addElement ('header', 'taskavailability', get_string ('codiana:section:availability', 'codiana'));
        {
            // task available from date
            $this->addTaskRangeFromElement ();

            // task available to date
            $this->addTaskRangeToElement ();
        }



        // -----------------------------------------------------------------------------
        // ----- LIMITS ----------------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'tasklimits', get_string ('codiana:section:limits', 'codiana'));
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
        // ----- FILES -----------------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'taskfiles', get_string ('codiana:section:files', 'codiana'));
        $this->mform->addHelpButton ('taskfiles', 'codiana:section:files', 'codiana');
        {
            // task solution file
            $this->addTaskSolutionFileElement ();

            // task input file
            $this->addTaskInputFileElement ();

            // task output file
            $this->addTaskOutputFileElement ();

            // task error file
            $this->addTaskErrorFileElement ();
        }


        // -----------------------------------------------------------------------------
        // ----- I/O EXAMPLES ----------------------------------------------------------
        // -----------------------------------------------------------------------------
        $this->mform->addElement ('header', 'taskexamples', get_string ('codiana:section:examples', 'codiana'));
        {
            // task input example
            $this->addTaskInputExample ();

            // task output example
            $this->addTaskOutputExample ();
        }


        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements ();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons ();

    }



    private function addTaskNameElement () {
        $this->mform->addElement ('text', 'name', get_string ('codiana:name', 'codiana'), array ('size' => '64'));
        $this->mform->setType ('name', PARAM_TEXT);
        $this->mform->addRule ('name', null, 'required', null, 'client');
        $this->mform->addRule ('name', null, 'required', null, 'server');
        $this->mform->addRule ('name', get_string ('maximumchars', '', 64), 'maxlength', 64, 'client');
        $this->mform->addHelpButton ('name', 'codiana:name', 'codiana');
    }



    private function addTaskMainFileNameElement () {
        $this->mform->addElement ('text', 'mainfilename', get_string ('codiana:mainfilename', 'codiana'), array ('size' => '64'));
        $this->mform->setType ('mainfilename', PARAM_TEXT);
        $this->mform->addRule ('mainfilename', null, 'required', null, 'client');
        $this->mform->addRule ('mainfilename', null, 'required', null, 'server');
        $this->mform->addRule ('mainfilename', get_string ('maximumchars', '', 64), 'maxlength', 64, 'client');
        $this->mform->addHelpButton ('mainfilename', 'codiana:mainfilename', 'codiana');
    }



    private function addTaskIntroElement () {
        $this->add_intro_editor ();
    }



    private function addTaskDifficultyElement () {
        $difficulty = array ('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5);
        $this->mform->addElement ('select', 'difficulty', get_string ('codiana:difficulty', 'codiana'), $difficulty);
        $this->mform->setType ('difficulty', PARAM_INT);
        $this->mform->getElement ('difficulty')->setSelected ('3');
        $this->mform->addHelpButton ('difficulty', 'codiana:difficulty', 'codiana');
    }



    private function addTaskGradeMethodElement () {
        $gradeMethod = array (
            '1' => get_string ('codiana:grademethod:strict', 'codiana'),
            '2' => get_string ('codiana:grademethod:tolerant', 'codiana'),
            '3' => get_string ('codiana:grademethod:vague', 'codiana')
        );
        $this->mform->addElement ('select', 'grademethod', get_string ('codiana:grademethod', 'codiana'), $gradeMethod);
        $this->mform->getElement ('grademethod')->setSelected ('2');
        $this->mform->addRule ('grademethod', null, 'required', null, 'client');
        $this->mform->addRule ('grademethod', null, 'required', null, 'server');
        $this->mform->setType ('grademethod', PARAM_INT);
        $this->mform->addHelpButton ('grademethod', 'codiana:grademethod', 'codiana');
    }



    private function addTaskLanguagesElement () {
        global $DB;
        $result = $DB->get_records ('codiana_language', null, '', 'extension,name');
        $languages = array ();
        foreach ($result as $language)
            $languages[$language->extension] = $language->name;

        reset ($languages);
        $selected = key ($languages);

        $this->mform->addElement ('select', 'languages', get_string ('codiana:languages', 'codiana'), $languages);
        $this->mform->getElement ('languages')->setSelected ($selected);
        $this->mform->getElement ('languages')->setMultiple (true);
        $this->mform->addRule ('languages', null, 'required', null, 'client');
        $this->mform->addRule ('languages', null, 'required', null, 'server');
        $this->mform->setType ('languages', PARAM_ALPHANUMEXT);
        $this->mform->addHelpButton ('languages', 'codiana:languages', 'codiana');
    }



    private function addTaskRangeFromElement () {
        $options = array (
            'startyear' => 2013,
            'stopyear' => 2014,
            'timezone' => 99,
            'step' => 15,
            'optional' => true
        );
        //TODO availablefromdate get_string ();!
        $this->mform->addElement ('date_time_selector', 'timeopen', get_string ('codiana:timeopen', 'codiana'), $options);
        $this->mform->setDefault ('timeopen', time ());
        $this->mform->addHelpButton ('timeopen', 'codiana:timeopen', 'codiana');
    }



    private function addTaskRangeToElement () {
        $options = array (
            'startyear' => 2013,
            'stopyear' => 2014,
            'timezone' => 99,
            'step' => 15,
            'optional' => true
        );
        $this->mform->addElement ('date_time_selector', 'timeclose', get_string ('codiana:timeclose', 'codiana'), $options);
        $this->mform->setDefault ('timeclose', time () + 60 * 60 * 24 * 7);
        $this->mform->addHelpButton ('timeclose', 'codiana:timeclose', 'codiana');
    }



    private function addTaskMaxUsersElement () {
        $maxUsersElement =& $this->mform->createElement ('text', 'maxusers', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'maxusers_enabled', '', get_string ('enable'));


        $this->mform->setDefault ('maxusers', 15);
        $this->mform->setDefault ('maxusers_enabled', 1);

        $items = array ($maxUsersElement, $checkboxElement);
        $this->mform->addGroup ($items, 'maxusers_group', get_string ('codiana:maxusers', 'codiana'), ' ', false);
        $this->mform->disabledIf ('maxusers_group', 'maxusers_enabled');
        $this->mform->setType ('maxusers', PARAM_INT);
        $this->mform->addHelpButton ('maxusers_group', 'codiana:maxusers', 'codiana');
    }



    private function addTaskMaxAttemptsElement () {
        $maxAttemptsElement =& $this->mform->createElement ('text', 'maxattempts', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'maxattempts_enabled', '', get_string ('enable'));

        $this->mform->setDefault ('maxattempts', 10);
        $this->mform->setDefault ('maxattempts_enabled', 1);

        $items = array ($maxAttemptsElement, $checkboxElement);
        $this->mform->addGroup ($items, 'maxattempts_group', get_string ('codiana:maxattempts', 'codiana'), ' ', false);
        $this->mform->disabledIf ('maxattempts_group', 'maxattempts_enabled');
        $this->mform->setType ('maxattempts', PARAM_INT);
        $this->mform->addHelpButton ('maxattempts_group', 'codiana:maxattempts', 'codiana');
    }



    private function addTaskLimitTimeElement () {
        $limitTimeFalling =& $this->mform->createElement ('text', 'limittimefalling', '');
        $limitTimeNothing =& $this->mform->createElement ('text', 'limittimenothing', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'limittime_enabled', '', get_string ('enable'));

        $this->mform->setDefault ('limittimefalling', 3000);
        $this->mform->setDefault ('limittimenothing', 6000);
        $this->mform->setDefault ('limittime_enabled', 1);

        $items = array ($limitTimeFalling, $limitTimeNothing, $checkboxElement);
        $this->mform->addGroup ($items, 'limittime_group', get_string ('codiana:limittime', 'codiana'), ' ', false);
        $this->mform->disabledIf ('limittime_group', 'limittime_enabled');
        $this->mform->setType ('limittimefalling', PARAM_INT);
        $this->mform->setType ('limittimenothing', PARAM_INT);
        $this->mform->addHelpButton ('limittime_group', 'codiana:limittime', 'codiana');
    }



    private function addTaskLimitMemoryElement () {
        $limitMemoryFalling =& $this->mform->createElement ('text', 'limitmemoryfalling', '');
        $limitMemoryNothing =& $this->mform->createElement ('text', 'limitmemorynothing', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'limitmemory_enabled', '', get_string ('enable'));

        $this->mform->setDefault ('limitmemoryfalling', 100);
        $this->mform->setDefault ('limitmemorynothing', 200);
        $this->mform->setDefault ('limitmemory_enabled', 1);

        $items = array ($limitMemoryFalling, $limitMemoryNothing, $checkboxElement);
        $this->mform->addGroup ($items, 'limitmemory_group', get_string ('codiana:limitmemory', 'codiana'), ' ', false);
        $this->mform->disabledIf ('limitmemory_group', 'limitmemory_enabled');
        $this->mform->setType ('limitmemoryfalling', PARAM_INT);
        $this->mform->setType ('limitmemorynothing', PARAM_INT);
        $this->mform->addHelpButton ('limitmemory_group', 'codiana:limitmemory', 'codiana');
    }



    private function addTaskFileElement ($fileTypeName, $required) {
        global $CFG;
        $this->mform->addElement (
            'filepicker',
            $fileTypeName,
            get_string ("codiana:$fileTypeName", 'codiana'),
            null,
            array ('maxbytes' => $CFG->maxbytes,
                   'accepted_types' => '*'));
        if ($required)
            $this->mform->addRule ($fileTypeName, "you must specify atleast one file", 'required', null, 'client');
        $this->mform->setType ($fileTypeName, PARAM_RAW);
        $this->mform->addHelpButton ($fileTypeName, "codiana:$fileTypeName", 'codiana');
    }



    private function addTaskSolutionFileElement () {
        $this->addTaskFileElement ('solutionfile', false);
    }



    private function addTaskInputFileElement () {
        $this->addTaskFileElement ('inputfile', false);
    }



    private function addTaskOutputFileElement () {
        $this->addTaskFileElement ('outputfile', false);
    }



    private function addTaskErrorFileElement () {
        $this->addTaskFileElement ('errorfile', false);
    }



    function validation ($data, $files) {
        global $CFG, $DB;
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


        if (intval (@$codiana->grademethod) == 0)
            $errors['grademethod'] = 'invalid value';


        if (!is_array (@$codiana->languages))
            $errors['languages'] = 'not array';
        else {
            global $DB;
            $languages = $DB->get_records ('codiana_language', null, '', 'extension');
            $languages = array_keys ($languages);
            foreach ($codiana->languages as $language) {
                if (in_array ($language, $languages) == false)
                    $errors['languages'] = 'Unsupported programming language ' . $language;
            }
        }


        if (intval (@$codiana->difficulty) == 0)
            $errors['difficulty'] = 'not number';


        if (intval (@$codiana->introeditor['format']) == 0)
            $errors['introformat'] = 'not number';

        $timeopen = isset ($codiana->timeopen) && $codiana->timeopen != 0
            ? intval (@$codiana->timeopen) : null;
        $timeclose = isset ($codiana->timeclose) && $codiana->timeclose != 0
            ? intval (@$codiana->timeclose) : null;
        if ((!is_null ($timeopen) && !is_null ($timeclose)) && $timeopen >= $timeclose) {
            $errors['timeopen'] = 'dates are colliding';
            $errors['timeclose'] = 'dates are colliding';
        }


        if (!codiana_check_int ($codiana, 'maxusers', false))
            $errors['maxusers_group'] = 'must be non zero integer';


        if (!codiana_check_int ($codiana, 'maxattempts', false))
            $errors['maxattempts_group'] = 'must be non zero integer';


        if (!codiana_check_int ($codiana, 'limittime', false))
            $errors['limittime_group'] = 'must be non zero integer';


        if (!codiana_check_int ($codiana, 'limitmemory', false))
            $errors['limitmemory_group'] = 'must be non zero integer';


        $codiana->solutionfile = @$codiana->solutionfile;
        $codiana->inputfile = @$codiana->inputfile;
        $codiana->outputfile = @$codiana->outputfile;
        $codiana->errorfile = @$codiana->errorfile;

        $codiana->visible = @$codiana->visible;
        $codiana->cmidnumber = @$codiana->cmidnumber;
        $codiana->groupmode = @$codiana->groupmode;

        return $errors;
    }



    /**
     * Filling form
     * @param array $toform data from DB
     */
    public function data_preprocessing (&$toform) {
        parent::data_preprocessing ($toform);

        if (isset ($toform['languages'])) {
            $index = 0;
            //TODO secure language values
            $value = trim ($toform['languages']);
            $number = 0;
            $values = array ();
            while ($number < $value) {
                $number = pow (2, $index++);
                if (($value & $number) > 0)
                    $values[] = $number;
            }
            $toform['languages'] = $values;
        }

        $checkFields = array (
            'maxusers', 'maxattempts', 'limittime', 'limitmemory',
            'timeopen', 'timeclose');

        foreach ($checkFields as $field)
            if (isset ($toform[$field]))
                $toform[$field . '_enabled'] = intval ($toform[$field]) > 0;
    }



    private function addTaskInputExample () {
        $this->mform->addElement ('textarea', 'inputexample', get_string ('codiana:inputexample', 'codiana'), 'wrap="virtual" rows="10" cols="100" class="codiana_monospaced"');
        $this->mform->setType ('inputexample', PARAM_TEXT);
        $this->mform->addHelpButton ('inputexample', 'codiana:inputexample', 'codiana');
    }



    private function addTaskOutputExample () {
        $this->mform->addElement ('textarea', 'outputexample', get_string ('codiana:outputexample', 'codiana'), 'wrap="virtual" rows="10" cols="100" class="codiana_monospaced"');
        $this->mform->setType ('outputexample', PARAM_TEXT);
        $this->mform->addHelpButton ('outputexample', 'codiana:outputexample', 'codiana');
    }
}
