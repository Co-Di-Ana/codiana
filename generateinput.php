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
 * This script controls the display of the quiz reports.
 *
 * @package    mod
 * @subpackage quiz
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once (dirname (__FILE__) . '/../../config.php');
require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
require_once ($CFG->dirroot . '/mod/codiana/formlib.php');

global $DB;

// grab course module id
$id = optional_param ('id', 0, PARAM_INT);

// try to find right module or throw error
if ($id) {
    if (!$cm = get_coursemodule_from_id ('codiana', $id)) {
        print_error ('invalidcoursemodule');
    }
    if (!$course = $DB->get_record ('course', array ('id' => $cm->course))) {
        print_error ('coursemisconf');
    }
    if (!$codiana = $DB->get_record ('codiana', array ('id' => $cm->instance))) {
        print_error ('invalidcoursemodule');
    }
} else {
    print_error ('invalidcoursemodule');
}

// check login and grap context
require_login ($course, false, $cm);

// ----- CAPABILITY managetaskfiles ----------------------------------------------------------------
$context = context_module::instance ($cm->id);
require_capability('mod/codiana:managetaskfiles', $context);

// clean-up URL
$url = new moodle_url('/mod/codiana/generateinput.php', array ('id' => $cm->id));
$PAGE->set_url ($url);
$PAGE->set_title ('Submit solution');
$PAGE->set_heading ("Submitting solution to '$codiana->name'");
$PAGE->set_pagelayout ('standard');
$PAGE->requires->jquery();
$PAGE->requires->js('/mod/codiana/html/js/sprintf.min.js', true);
$PAGE->requires->js('/mod/codiana/html/js/generateinput.js', true);
$PAGE->requires->css('/mod/codiana/html/css/generateinput.css');
$PAGE->requires->css('/mod/codiana/html/css/view.css');
global $OUTPUT;


echo $OUTPUT->header ();

require_once ($CFG->dirroot . '/mod/codiana/html/generateinput.html');
$mform = new mod_codiana_generate_input($codiana, $url);

// Form processing and displaying
if ($mform->is_cancelled ()) {
    // form canceled
} else if ($data = $mform->get_data ()) {

    // form is valid, now file check
    $result = codiana_generate_input ($codiana);

    if ($result != null)
        echo $result->renderAll();
    else
        echo codiana_message::create('filegenerated', 'success')->renderAll();
}
$mform->display();

echo $OUTPUT->footer ();