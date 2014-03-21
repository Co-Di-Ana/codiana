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
global $DB;


// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id (null, false);


// grab course module id
$userID    = optional_param ('userid', CODIANA_ALL_USERS_ID, PARAM_INT);
$attemptID = optional_param ('attemptid', null, PARAM_INT);

// check login and grap context
require_login ($course, false, $cm);
$context = context_module::instance ($cm->id);

// Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
if ($context->contextlevel != CONTEXT_MODULE) {
    print_error ('error:youcannotdownloadthisfile', 'codiana');
}

// check login and grap context
require_login ($course, false, $cm);


// clean-up URL
$url = new moodle_url(
    '/mod/codiana/checkplags.php',
    array (
          'id'        => $cm->id,
          'userid'    => $userID,
          'attemptid' => $attemptID
    )
);



// throw error if user doesn't have sufficient permissions
// TODO dict
if (!has_capability ('mod/codiana:manager', $context))
    print_error ('error:youcannotperformplagiarismcheck', 'codiana');


// if solutions exists change its state
// either in attempt or task
if ($userID != CODIANA_ALL_USERS_ID) {
    $attempt = codiana_get_attempt ($codiana, $attemptID);
    if ($attempt === false)
        // TODO dict
        redirect (new moodle_url('/mod/codiana/viewresults.php', array ('id' => $cm->id)), codiana_string ('error:solutiondoesnotexists'));

    $attempt->plagscheckstate = codiana_plag_state::WAITING_TO_PROCESS;
    $attempt->plagstimecheck  = time ();

    // save new plagcheckstate and time
    $result = codiana_set_attempt ($attempt);
} else {
    $codiana->plagscheckstate = codiana_plag_state::WAITING_TO_PROCESS;
    $codiana->plagstimecheck  = time ();

    // save new plagcheckstate and time
    codiana_set_task ($codiana);
}


// delete previous unfinished items in queue
codiana_delete_previous_plags ($codiana, $userID);


$queue = array (
    'taskid'    => $codiana->id,
    'userid'    => $userID,
    'attemptid' => $attemptID,
    'type'      => codiana_queue_type::PLAGIARISM_CHECK,
    'priority'  => 101,
);


$queueID = $DB->insert_record (
    'codiana_queue',
    (object)$queue,
    true
);


codiana_trigger_java_update ();


if ($userID == CODIANA_ALL_USERS_ID)
    $message = codiana_string ('message:taskplagcheckinserted');
else
    $message = codiana_string ('message:soluitonplagcheckinserted');


redirect (new moodle_url('/mod/codiana/viewresults.php', array ('id' => $cm->id)), $message);
