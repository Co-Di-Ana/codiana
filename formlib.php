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
 * Base class for the settings form for {@link quiz_attempts_report}s.
 *
 * @package   mod_quiz
 * @copyright 2012 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined ('MOODLE_INTERNAL') || die();

require_once ($CFG->libdir . '/formslib.php');


/**
 * Base class for the settings form for {@link quiz_attempts_report}s.
 *
 * @copyright 2012 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_codiana_submitsolution_form extends moodleform {


    public $extension;

    protected function definition () {
        global $CFG, $DB;
        $mform = $this->_form;


        $result = $DB->get_records ('codiana_language', null, '', 'extension,name');
        $languages = array ();
        $filter = '*.zip';
        foreach ($result as $language) {
            $languages[$language->extension] = $language->name;
            $filter .= ',*.' . $language->extension;
        }
        reset ($languages);
        $selected = key ($languages);


        // solution extension if zip
        $mform->addElement ('select', 'language', get_string ('codiana:solutionlanguage', 'codiana'), $languages);
        $mform->getElement ('language')->setSelected ($selected);
        $mform->getElement ('language')->setMultiple (false);
        $mform->addRule ('language', null, 'required', null, 'client');
        $mform->addRule ('language', null, 'required', null, 'server');
        $mform->setType ('language', PARAM_ALPHANUMEXT);
        $mform->addHelpButton ('language', 'codiana:solutionlanguage', 'codiana');

        // solution file
        $mform->addElement ('filepicker', 'sourcefile', get_string ('codiana:sourcefile', 'codiana'), null,
            array (
                'maxbytes' => $CFG->maxbytes,
                'accepted_types' => $filter
            )
        );
        $mform->setType ('sourcefile', PARAM_RAW);
        $mform->addRule ('sourcefile', 'you must specify atleast one file', 'required', null, 'client');
        $mform->addHelpButton ('sourcefile', 'codiana:sourcefile', 'codiana');

        $this->add_action_buttons (true, get_string ('codiana:submitsolution:submit', 'codiana'));
    }


    // Perform some extra moodle validation
//    function validation($data, $files) {
//        echo "-validation-<br />";
//        $errors = parent::validation($data, $files);
//        $this->validate_solution_file($errors);
//        return $errors;
//    }

    public function validate_solution_file () {
        global $CFG, $DB;
        require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
        $solution = (object)$this->get_data ();
        $extension = codiana_solution_get_extension ($this, 'sourcefile');
        $extension = $extension == 'zip' ? $solution->language : $extension;
        $this->extension = $extension;

        $languages = $DB->get_records ('codiana_language', null, '', 'extension');
        $languages = array_keys ($languages);

        // unknown extension check
        if (in_array($extension, $languages) == false || true) {
            $this->_errors['sourcefile'] = 'Unknown extension';
        }
    }
}