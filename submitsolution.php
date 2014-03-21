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

// ----- CAPABILITY submitsolution ----------------------------------------------------------------
$context = context_module::instance ($cm->id);
require_capability ('mod/codiana:submitsolution', $context);

// TODO dict
// is task open?
if (!codiana_is_task_open ($codiana))
    print_error ('error:tasknotopen', 'codiana');

// task not active
if (!codiana_is_task_active ($codiana))
    print_error ('error:tasknotactive', 'codiana');


// clean-up URL
$url = new moodle_url('/mod/codiana/submitsolution.php', array ('id' => $cm->id));

$PAGE->set_url ($url);
$PAGE->set_title (codiana_string ('title:submit_solution'));
$PAGE->set_heading (codiana_create_page_title($codiana, 'title:submit_solution'));
$PAGE->set_pagelayout ('standard');
global $OUTPUT;


//# ----- OUTPUT ----------------------------------------------------------------


// grap total attempts and max attempts
$totalAttempts = codiana_get_user_attempt_count ($codiana, $USER->id);
$maxAttempts   = is_null ($codiana->maxattempts) ? INF : $codiana->maxattempts;
$canSubmit     = $totalAttempts < $maxAttempts || has_capability ('mod/codiana:manager', $context);
$lastAttempt   = codiana_get_last_attempt ($codiana, $USER->id);
$warning       = $totalAttempts > 0 && $lastAttempt->state == codiana_attempt_state::WAITING_TO_PROCESS;

// grap total user count in this task
$totalUsers = codiana_get_user_count ($codiana, $USER->id);
$maxUsers   = $codiana->maxusers;


if (!$canSubmit) {
    echo $OUTPUT->header ();
    echo html_writer::tag ('h1', codiana_string (
        'message:no_more_attempts_x_of_x',
        $totalAttempts, $maxAttempts
    ));
    echo html_writer::tag ('p', codiana_string ('message:maximum_no_of_attempts_reached'));
    echo $OUTPUT->footer ();

} else {


    $mform = new mod_codiana_submitsolution_form ($codiana, $url);

// Form processing and displaying
    if ($mform->is_cancelled ()) {
        // form canceled
        redirect (new moodle_url('/mod/codiana/view.php', array ('id' => $cm->id)));
    } else if ($data = $mform->get_data ()) {
        // form is valid, now file check


        // file extension support check
        $error = $mform->validate_solution_file ();
        if ($error != null) {
            redirect ($url, sprintf ($error, $mform->extension));
            die();
        }

        // max attempts check
        if ($totalAttempts >= $maxAttempts) {
            print_error ('nomoreattempts', 'codiana');
        }

        // save solution
        $data = array (
            'priority'    => has_capability ('mod/codiana:manager', $context) ? 100 : 0,
            'elementName' => 'sourcefile',
            'attempt'     => $totalAttempts + 1,
            'type'        => codiana_queue_type::SOLUTION_CHECK
        );
        codiana_save_user_solution ($codiana, $USER->id, $mform, $data);


        if ($warning) {
            // set state to aborted (last attempt) and delete any users queue items
            $lastAttempt->state = codiana_attempt_state::PROCESS_ABORTED;
            $DB->update_record ('codiana_attempt', $lastAttempt);
            $DB->delete_records ('codiana_queue', array ('userid' => $USER->id));
        }

        // TODO -1 nepocitat
        // redirect user to view
        $newAttemptNo = $totalAttempts + 1;
        redirect (new moodle_url('/mod/codiana/view.php', array ('id' => $cm->id)), codiana_string ('message:uploaded_x_attempt', $newAttemptNo));

    } else {
        // show form

        echo $OUTPUT->header ();
        echo html_writer::tag ('h2', codiana_string (
            'message:attempt_x_from_x',
            $totalAttempts + 1,
            is_infinite ($maxAttempts) ? "∞" : "$maxAttempts"
        ));
        $mform->display ($codiana, $cm, $course);

        if ($warning)
            echo html_writer::tag ('h4', codiana_string ('warning:abortedsolution'));
    }

    // TODO cancel empty screen? :D

    echo $OUTPUT->footer ();
}
