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
 * Prints a particular instance of codiana
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_codiana
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once (dirname (__FILE__) . '/../../config.php');
require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
require_once ($CFG->libdir . '/gradelib.php');

// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id ();

require_login ($course, true, $cm);
$context = context_module::instance ($cm->id);

add_to_log ($course->id, 'codiana', 'view', "view.php?id={$cm->id}", $codiana->name, $cm->id);

// Print the page header

$PAGE->set_url ('/mod/codiana/view.php', array ('id' => $cm->id));
$PAGE->set_title (format_string ($codiana->name));
$PAGE->set_heading (codiana_create_page_title ($codiana));
$PAGE->set_context ($context);
$PAGE->requires->css ('/mod/codiana/html/css/view.css');
$PAGE->requires->jquery ();
$PAGE->requires->js ('/mod/codiana/html/js/Chart.min.js', true);
/** @var mod_codiana_renderer */
$output = $PAGE->get_renderer ('mod_codiana');
$output->init ($codiana, $cm, $context, $course, null);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('codiana-'.$somevar);




//# ----- OUTPUT ----------------------------------------------------------------

// Output starts here
echo $OUTPUT->header ();

if (has_capability ('mod/codiana:manager', $context)) {
    $resolutions = codiana_get_stat_resolution ($codiana);

    $stateStat       = codiana_get_stat ($codiana, 'state', $resolutions->state);
    $resultFinalStat = codiana_get_stat ($codiana, 'resultfinal', $resolutions->resultfinal);
    $timeStat        = codiana_get_stat ($codiana, 'runtime', $resolutions->runtime);
    $memoryStat      = codiana_get_stat ($codiana, 'runmemory', $resolutions->runmemory);

    $stats = array (
        'state_stat'       => array ('data' => $stateStat, 'res' => $resolutions->state),
        'resultfinal_stat' => array ('data' => $resultFinalStat, 'res' => $resolutions->resultfinal),
        'time_stat'        => array ('data' => $timeStat, 'res' => $resolutions->runtime),
        'memory_stat'      => array ('data' => $memoryStat, 'res' => $resolutions->runmemory),
    );

    echo $output->view_task_details (true);
    echo $output->view_task_stats ($stats);
} else {
    $grades = grade_get_grades ($course->id, 'mod', 'codiana', $codiana->id, $USER->id);
    $grade  = @$grades->items[0]->grades[$USER->id];

    $attempt = codiana_get_user_grade_attempt ($codiana, $USER->id, array ('attempt.id', 'timesent', 'state', 'resultnote'));

    if (codiana_is_task_open ($codiana) && codiana_is_task_active ($codiana)) {
        echo $output->view_task_details (false, $grade, $attempt);
    } else {
        echo $output->view_page_not_active ($grade);
    }

}

// Finish the page
echo $OUTPUT->footer ();