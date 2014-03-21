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

// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id ();

// check login and grap context
require_login ($course, false, $cm);

// ----- CAPABILITY viewmyattempts ----------------------------------------------------------------
$context = context_module::instance ($cm->id);
require_capability ('mod/codiana:viewmyattempts', $context);

// TODO dict
// has task started
if (!codiana_has_task_started ($codiana))
    print_error ('error:tasknotstarted', 'codiana');

// task not active
if (!codiana_is_task_active ($codiana))
    print_error ('error:tasknotactive', 'codiana');


// clean-up URL
$url = new moodle_url('/mod/codiana/viewmyattempts.php', array ('id' => $cm->id));
$PAGE->set_url ($url);
$PAGE->set_title (codiana_string ('title:view_my_attempts'));
$PAGE->set_heading (codiana_create_page_title($codiana, 'title:view_my_attempts'));
$PAGE->set_pagelayout ('standard');
$output = $PAGE->get_renderer ('mod_codiana');
$output->init ($codiana, $cm, $context, $course);
global $OUTPUT;




//# ----- OUTPUT ----------------------------------------------------------------


// manager gets all fields
//if (has_capability ('mod/codiana:manager', $context)) {
//    $fields = codiana_display_options::$fields;
//} else {
$fields = codiana_get_task_fields (
    $codiana, codiana_is_task_open ($codiana) ?
    codiana_display_options::OPEN_SOLVER :
    codiana_display_options::CLOSE_SOLVER);
//}

$mysqlFields = codiana_expand_fields ($fields);
// user do not need to see their own username
unset ($mysqlFields['username']);

// grab results
$allAttempts  = codiana_get_user_all_attempts ($codiana, $USER->id, $mysqlFields);
$gradeAttempt = codiana_get_user_grade_attempt ($codiana, $USER->id, $mysqlFields);


echo $OUTPUT->header ();
echo $output->view_page_viewmyattempts ($gradeAttempt, $allAttempts);
echo $OUTPUT->footer ();