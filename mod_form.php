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
        $this->mform->addElement ('text', 'name', get_string ('codiana:taskname', 'codiana'), array ('size' => '64'));
        $this->mform->setType ('name', PARAM_TEXT);
        $this->mform->addRule ('name', null, 'required', null, 'client');
        $this->mform->addRule ('name', null, 'required', null, 'server');
        $this->mform->addRule ('name', get_string ('maximumchars', '', 64), 'maxlength', 64, 'client');
        $this->mform->addHelpButton ('name', 'codiana:taskname', 'codiana');
    }



    private function addTaskMainFileNameElement () {
        $this->mform->addElement ('text', 'taskmainfilename', get_string ('codiana:taskmainfilename', 'codiana'), array ('size' => '64'));
        $this->mform->setType ('taskmainfilename', PARAM_TEXT);
        $this->mform->addRule ('taskmainfilename', null, 'required', null, 'client');
        $this->mform->addRule ('taskmainfilename', null, 'required', null, 'server');
        $this->mform->addRule ('taskmainfilename', get_string ('maximumchars', '', 64), 'maxlength', 64, 'client');
        $this->mform->addHelpButton ('taskmainfilename', 'codiana:taskmainfilename', 'codiana');
    }



    private function addTaskIntroElement () {
        $this->add_intro_editor ();
    }



    private function addTaskDifficultyElement () {
        $difficulty = array ('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5);
        $this->mform->addElement ('select', 'taskdifficulty', get_string ('codiana:taskdifficulty', 'codiana'), $difficulty);
        $this->mform->setType ('taskdifficulty', PARAM_INT);
        $this->mform->getElement ('taskdifficulty')->setSelected ('3');
        $this->mform->addHelpButton ('taskdifficulty', 'codiana:taskdifficulty', 'codiana');
    }



    private function addTaskGradeMethodElement () {
        $gradeMethod = array (
            '1' => get_string ('codiana:taksgrademethod:strict', 'codiana'),
            '2' => get_string ('codiana:taksgrademethod:tolerant', 'codiana'),
            '3' => get_string ('codiana:taksgrademethod:vague', 'codiana')
        );
        $this->mform->addElement ('select', 'taskgrademethod', get_string ('codiana:taskgrademethod', 'codiana'), $gradeMethod);
        $this->mform->getElement ('taskgrademethod')->setSelected ('2');
        $this->mform->addRule ('taskgrademethod', null, 'required', null, 'client');
        $this->mform->addRule ('taskgrademethod', null, 'required', null, 'server');
        $this->mform->setType ('taskgrademethod', PARAM_INT);
        $this->mform->addHelpButton ('taskgrademethod', 'codiana:taskgrademethod', 'codiana');
    }



    private function addTaskLanguagesElement () {
        global $DB;
        $result = $DB->get_records ('codiana_language', null, '', 'id,name');
        $languages = array ();
        foreach ($result as $language)
            $languages[$language->id] = $language->name;

        $this->mform->addElement ('select', 'tasklanguages', get_string ('codiana:tasklanguages', 'codiana'), $languages);
        $this->mform->getElement ('tasklanguages')->setSelected ('1');
        $this->mform->getElement ('tasklanguages')->setMultiple (true);
        $this->mform->addRule ('tasklanguages', null, 'required', null, 'client');
        $this->mform->addRule ('tasklanguages', null, 'required', null, 'server');
        $this->mform->setType ('tasklanguages', PARAM_INT);
        $this->mform->addHelpButton ('tasklanguages', 'codiana:tasklanguages', 'codiana');
    }



    private function addTaskRangeFromElement () {
        $options = array (
            'startyear' => 2013,
            'stopyear' => 2014,
            'timezone' => 99,
            'step' => 15,
            'optional' => true
        );
        //availablefromdate get_string ();!
        $this->mform->addElement ('date_time_selector', 'tasktimeopen', get_string ('codiana:tasktimeopen', 'codiana'), $options);
        $this->mform->addHelpButton ('tasktimeopen', 'codiana:tasktimeopen', 'codiana');
    }



    private function addTaskRangeToElement () {
        $options = array (
            'startyear' => 2013,
            'stopyear' => 2014,
            'timezone' => 99,
            'step' => 15,
            'optional' => true
        );
        $this->mform->addElement ('date_time_selector', 'tasktimeclose', get_string ('codiana:tasktimeclose', 'codiana'), $options);
        $this->mform->addHelpButton ('tasktimeclose', 'codiana:tasktimeclose', 'codiana');
    }



    private function addTaskMaxUsersElement () {
        $maxUsersElement =& $this->mform->createElement ('text', 'taskmaxusers', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'taskmaxusers_enabled', '', get_string ('enable'));

        $items = array ($maxUsersElement, $checkboxElement);
        $this->mform->addGroup ($items, 'taskmaxusers_group', get_string ('codiana:taskmaxusers', 'codiana'), ' ', false);
        $this->mform->disabledIf ('taskmaxusers_group', 'taskmaxusers_enabled');
        $this->mform->setType ('taskmaxusers', PARAM_INT);
        $this->mform->addHelpButton ('taskmaxusers_group', 'codiana:taskmaxusers', 'codiana');
    }



    private function addTaskMaxAttemptsElement () {
        $items = array ();
        $items[] =& $this->mform->createElement ('text', 'taskmaxattempts', '');
        $items[] =& $this->mform->createElement ('checkbox', 'taskmaxattempts_enabled', '', get_string ('enable'));
        $this->mform->addGroup ($items, 'taskmaxattempts_group', get_string ('codiana:taskmaxattempts', 'codiana'), ' ', false);
        $this->mform->disabledIf ('taskmaxattempts_group', 'taskmaxattempts_enabled');
        $this->mform->setType ('taskmaxattempts', PARAM_INT);
        $this->mform->addHelpButton ('taskmaxattempts_group', 'codiana:taskmaxattempts', 'codiana');
    }



    private function addTaskLimitTimeElement () {
        $maxUsersElement =& $this->mform->createElement ('text', 'tasklimittime', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'tasklimittime_enabled', '', get_string ('enable'));

        $items = array ($maxUsersElement, $checkboxElement);
        $this->mform->addGroup ($items, 'tasklimittime_group', get_string ('codiana:tasklimittime', 'codiana'), ' ', false);
        $this->mform->disabledIf ('tasklimittime_group', 'tasklimittime_enabled');
        $this->mform->setType ('tasklimittime', PARAM_INT);
        $this->mform->addHelpButton ('tasklimittime_group', 'codiana:tasklimittime', 'codiana');
    }



    private function addTaskLimitMemoryElement () {
        $maxUsersElement =& $this->mform->createElement ('text', 'tasklimitmemory', '');
        $checkboxElement =& $this->mform->createElement ('checkbox', 'tasklimitmemory_enabled', '', get_string ('enable'));

        $items = array ($maxUsersElement, $checkboxElement);
        $this->mform->addGroup ($items, 'tasklimitmemory_group', get_string ('codiana:tasklimitmemory', 'codiana'), ' ', false);
        $this->mform->disabledIf ('tasklimitmemory_group', 'tasklimitmemory_enabled');
        $this->mform->setType ('tasklimitmemory', PARAM_INT);
        $this->mform->addHelpButton ('tasklimitmemory_group', 'codiana:tasklimitmemory', 'codiana');
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
        $this->addTaskFileElement ('tasksolutionfile', false);
    }



    private function addTaskInputFileElement () {
        $this->addTaskFileElement ('taskinputfile', false);
    }



    private function addTaskOutputFileElement () {
        $this->addTaskFileElement ('taskoutputfile', false);
    }



    private function addTaskErrorFileElement () {
        $this->addTaskFileElement ('taskerrorfile', false);
    }



    function validation ($data, $files) {
        global $CFG, $DB;
        require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
        $errors = parent::validation ($data, $files);


        $codiana = (object)$data;

        $tmp = trim (@$codiana->name);
        if (empty($tmp))
            $errors['name'] = 'empty';


        $tmp = trim (@$codiana->taskmainfilename);
        if (empty($tmp))
            $errors['taskmainfilename'] = 'empty';
        else if (preg_match ('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tmp) != 1)
            $errors['taskmainfilename'] = 'regexp';


        if (intval (@$codiana->taskgrademethod) == 0)
            $errors['taskgrademethod'] = 'invalid value';


        if (!is_array (@$codiana->tasklanguages))
            $errors['tasklanguages'] = 'not array';
        else {
            global $DB;
            $languages = $DB->get_records ('codiana_language', null, '', 'id');
            $languages = array_keys ($languages);
            foreach ($codiana->tasklanguages as $language) {
                if (in_array ($language, $languages) == false)
                    $errors['tasklanguages'] = 'Unsupported programming language ' . $language;
            }
        }


        if (intval (@$codiana->taskdifficulty) == 0)
            $errors['taskdifficulty'] = 'not number';


        if (intval (@$codiana->introeditor['format']) == 0)
            $errors['introformat'] = 'not number';

        $timeopen = isset ($codiana->tasktimeopen) && $codiana->tasktimeopen != 0
            ? intval (@$codiana->tasktimeopen) : null;
        $timeclose = isset ($codiana->tasktimeclose) && $codiana->tasktimeclose != 0
            ? intval (@$codiana->tasktimeclose) : null;
        if ((!is_null ($timeopen) && !is_null ($timeclose)) && $timeopen >= $timeclose) {
            $errors['tasktimeopen'] = 'dates are colliding';
            $errors['tasktimeclose'] = 'dates are colliding';
        }


        if (!codiana_check_int ($codiana, 'taskmaxusers', false))
            $errors['taskmaxusers_group'] = 'must be non zero integer';


        if (!codiana_check_int ($codiana, 'taskmaxattempts', false))
            $errors['taskmaxattempts_group'] = 'must be non zero integer';


        if (!codiana_check_int ($codiana, 'tasklimittime', false))
            $errors['tasklimittime_group'] = 'must be non zero integer';


        if (!codiana_check_int ($codiana, 'tasklimitmemory', false))
            $errors['tasklimitmemory_group'] = 'must be non zero integer';


        $codiana->tasksolutionfile = @$codiana->tasksolutionfile;
        $codiana->taskinputfile = @$codiana->taskinputfile;
        $codiana->taskoutputfile = @$codiana->taskoutputfile;
        $codiana->taskerrorfile = @$codiana->taskerrorfile;

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
//        echo '<pre>';
//        print_r ($toform);
//        die ();

        if (isset ($toform['tasklanguages'])) {
            $index = 0;
            $value = intval ($toform['tasklanguages']);
            $number = 0;
            $values = array ();
            while ($number < $value) {
                $number = pow (2, $index++);
                if (($value & $number) > 0)
                    $values[] = $number;
            }
            $toform['tasklanguages'] = $values;
        }

        $checkFields = array (
            'taskmaxusers', 'taskmaxattempts', 'tasklimittime', 'tasklimitmemory',
            'tasktimeopen', 'tasktimeclose');

        foreach ($checkFields as $field)
            if (isset ($toform[$field]))
                $toform[$field . '_enabled'] = intval ($toform[$field]) > 0;
    }



    private function addTaskInputExample () {
        $this->mform->addElement ('textarea', 'taskinputexample', get_string ('codiana:taskinputexample', 'codiana'), 'wrap="virtual" rows="10" cols="100" class="codiana_monospaced"');
        $this->mform->setType ('taskinputexample', PARAM_TEXT);
        $this->mform->addHelpButton ('taskinputexample', 'codiana:taskinputexample', 'codiana');
    }



    private function addTaskOutputExample () {
        $this->mform->addElement ('textarea', 'taskoutputexample', get_string ('codiana:taskoutputexample', 'codiana'), 'wrap="virtual" rows="10" cols="100" class="codiana_monospaced"');
        $this->mform->setType ('taskoutputexample', PARAM_TEXT);
        $this->mform->addHelpButton ('taskoutputexample', 'codiana:taskoutputexample', 'codiana');
    }
}
