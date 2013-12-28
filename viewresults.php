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
$id      = optional_param ('id', 0, PARAM_INT);
$showAll = optional_param ('all', '0', PARAM_INT);
$showAll = $showAll == 1;

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

// ----- CAPABILITY viewresults ----------------------------------------------------------------
$context = context_module::instance ($cm->id);
require_capability ('mod/codiana:viewresults', $context);

// clean-up URL
$url = new moodle_url('/mod/codiana/viewresults.php', array ('id' => $cm->id));
$PAGE->set_url ($url);
$PAGE->set_title ('My modules page title');
$PAGE->set_heading ('My modules page heading');
$PAGE->set_pagelayout ('standard');
$output = $PAGE->get_renderer ('mod_codiana');
$output->init ($codiana, $cm, $context, $course);
global $OUTPUT;




//# ----- OUTPUT ----------------------------------------------------------------

$attempts = $showAll ? codiana_get_task_all_attempts ($codiana) : codiana_get_task_grade_attempts ($codiana);

echo $OUTPUT->header ();
echo $output->view_page_viewresults ($attempts, $showAll);
echo $OUTPUT->footer ();