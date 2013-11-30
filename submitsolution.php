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

// clean-up URL
$url = new moodle_url('/mod/codiana/submitsolution.php', array ('id' => $cm->id));

$PAGE->set_url ($url);
$PAGE->set_title ('Submit solution');
$PAGE->set_heading ("Submitting solution to '$codiana->name'");
$PAGE->set_pagelayout ('standard');
$context = context_module::instance ($cm->id);
global $OUTPUT;




//# ----- OUTPUT ----------------------------------------------------------------


// grap total attempts and max attempts
$totalAttempts = $DB->count_records ('codiana_attempt', array ('userid' => $USER->id, 'taskid' => $codiana->id));
$maxAttempts = $DB->get_field ('codiana', 'maxattempts', array ('id' => $codiana->id), MUST_EXIST);
$canSubmit = $totalAttempts < $maxAttempts;

if (!$canSubmit) {
    echo $OUTPUT->header ();
    echo html_writer::tag ('h1', 'No more attempts');
    echo html_writer::tag ('p', 'You have reached limit of maximum possible attempts.');
    echo $OUTPUT->footer ();
} else {



    $mform = new mod_codiana_submitsolution_form ($codiana, $url);

// Form processing and displaying
    if ($mform->is_cancelled ()) {
        // form canceled
        echo $OUTPUT->header ();
    } else if ($data = $mform->get_data ()) {
        // form is valid, now file check

        // file extension support check
        $error = $mform->validate_solution_file ($codiana);
        if ($error != null) {
            redirect ($url, sprintf ($error, $mform->extension));
            die();
        }

        // max attempts check
        if ($totalAttempts >= $maxAttempts) {
            print_error ('nomoreattempts', 'codiana');
        }


        // creating path pieces
        $dataDir = get_config ('codiana', 'storagepath');
        $codianaID = sprintf ('task-%04d', $codiana->id);
        $userID = sprintf ('user-%05d', $USER->id);
        $newAttempt = sprintf ('attempt-%03d', $totalAttempts + 1);
        $mainfilename = $codiana->mainfilename;

        // grap codiana_get_file_transfer object
        $fileTransfer = codiana_get_file_transfer ();

        // delete old solution
        $fileTransfer->deleteDir ("$dataDir/$codianaID/$userID/curr/");
        $fileTransfer->mkDir ("$dataDir/$codianaID/$userID/curr/");

        // save new solution
        $fileTransfer->saveFile ($mform->get_file_content ('sourcefile'),
                                 "$dataDir/$codianaID/$userID/curr/$mainfilename.$mform->defaultExtension");

        // if zipped solution, unzip to location and remove zip file
        if ($mform->defaultExtension == 'zip') {
            $fileTransfer->unzip ("$dataDir/$codianaID/$userID/curr/$mainfilename.$mform->defaultExtension",
                                  "$dataDir/$codianaID/$userID/curr/");
            $fileTransfer->deleteFile ("$dataDir/$codianaID/$userID/curr/$mainfilename.$mform->defaultExtension");
        }

        // zip solution
        $fileTransfer->mkDir ("$dataDir/$codianaID/$userID/curr/");
        $fileTransfer->mkDir ("$dataDir/$codianaID/$userID/prev/");
        $fileTransfer->zipDir ("$dataDir/$codianaID/$userID/curr/",
                               "$dataDir/$codianaID/$userID/prev/$newAttempt.zip");

        // TODO use is_zip comparison, not == 'zip'
        $attempt = array (
            'taskid' => $codiana->id,
            'userid' => $USER->id,
            'timesent' => time (),
            'state' => 0,
            'language' => $mform->extension,
            'detail' => $mform->defaultExtension == 'zip' ? 1 : 0,
            'ordinal' => $totalAttempts + 1
        );

        $attemptID = $DB->insert_record (
            'codiana_attempt',
            (object)$attempt,
            true
        );



        $queue = array (
            'taskid' => $codiana->id,
            'userid' => $USER->id,
            'attemptid' => $attemptID,
            'type' => 0, // solution
            'priority' => has_capability ('mod/codiana:queueimportance', $context) ? 1 : 0
        );

        $queueID = $DB->insert_record (
            'codiana_queue',
            (object)$queue,
            true
        );

        // redirect user to view
        redirect (new moodle_url('/mod/codiana/view.php', array ('id' => $cm->id)), "uploaded $newAttempt");

    } else {
        // show form
        echo $OUTPUT->header ();
        echo html_writer::tag ('h2', sprintf ('Attempt %d / %d', $totalAttempts + 1, $maxAttempts));
        $mform->display ($codiana, $cm, $course);
    }


    echo $OUTPUT->footer ();

}
