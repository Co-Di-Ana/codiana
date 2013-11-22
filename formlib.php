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


    protected function definition () {
        global $CFG;
        $mform = $this->_form;

//        $mform->addElement('text', 'name', get_string('codiananame', 'codiana'), array('size'=>'64'));
//        $mform->setType('name', PARAM_TEXT);
//
//        $mform->addElement('header', 'codianafieldset', get_string('codianafieldset', 'codiana'));
//
//        $mform->addElement('text', 'label2', 'codianasetting2', 'Your codiana fields go here. Replace me!');
//        $mform->setType('label2', PARAM_EMAIL);
//        $mform->addRule('label2', 'email je povinný', 'required');
//
//        $mform->addElement('filepicker', 'label3', 'codianasetting3', null, array('maxbytes' => 1, 'accepted_types' => '*'));
//        $mform->setType('label3', PARAM_FILE);

        $mform->addElement (
            'filepicker',
            'sourcefile',
            get_string ('file'),
            null,
            array ('maxbytes' => $CFG->maxbytes,
                   'accepted_types' => '*'));
        $mform->setType ('sourcefile', PARAM_RAW);
        $mform->addRule ('sourcefile', "you must specify atleast one file", "required", null, 'client');

        $this->add_action_buttons (true, get_string ('codiana:submitsolution:submit', 'codiana'));
    }
}