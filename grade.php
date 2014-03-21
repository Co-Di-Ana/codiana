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
require_once ($CFG->libdir . '/gradelib.php');

global $DB, $USER;

// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id ();

require_login ($course, true, $cm);
$context = context_module::instance ($cm->id);
if (!has_capability ('mod/codiana:manager', $context)) {
    redirect (new moodle_url('/mod/codiana/view.php', array ('id' => $cm->id)));
}
// Print the page header
$url = new moodle_url('/mod/codiana/grade.php', array ('id' => $cm->id));
$PAGE->set_url ($url);
$PAGE->set_title (codiana_string ('title:grading'));
$PAGE->set_heading (codiana_create_page_title ($codiana, 'title:grading'));
$PAGE->set_context ($context);
$PAGE->requires->css ('/mod/codiana/html/css/view.css');
//$PAGE->requires->jquery ();
/** @var mod_codiana_renderer */
$output = $PAGE->get_renderer ('mod_codiana');



$output->init ($codiana, $cm, $context, $course, null);


$ids = array ();
// get all enrolled users
$users = get_enrolled_users ($context, 'mod/codiana:submitsolution', 0, "u.id, CONCAT_WS (' ', UPPER(u.lastname), u.firstname) AS username");
foreach ($users as $user) array_push ($ids, $user->id);

// get grades or create null pseudograde
$grades = grade_get_grades ($course->id, 'mod', 'codiana', $codiana->id, $ids);
if (empty ($grades->items)) {
    $result = grade_update ('mod/codiana', $course->id, 'mod', 'codiana', $codiana->id, 0, null);
    $grades = grade_get_grades ($course->id, 'mod', 'codiana', $codiana->id, $ids);
}

// get all attempts
$attempts = codiana_get_task_grade_attempts ($codiana);



//# ----- OUTPUT ----------------------------------------------------------------

// TODO duplicate task

// Output starts here
echo $OUTPUT->header ();

echo $output->view_page_show_grades ($grades, $users, $attempts);

// Finish the page
echo $OUTPUT->footer ();