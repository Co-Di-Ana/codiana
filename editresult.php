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

// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id ();

require_login ($course, true, $cm);
$context = context_module::instance ($cm->id);
require_capability ('mod/codiana:manager', $context);


$attemptID = required_param ('attemptid', PARAM_INT);
$edit      = optional_param ('btn:edit', null, PARAM_RAW);
$edit      = !is_null ($edit);
//echo '<pre>';
//print_r ($attemptID);
//var_dump ($edit);
//die ();

// clean-up URL
$url = new moodle_url('/mod/codiana/viewresults.php', array ('id' => $cm->id, 'attemptid' => $attemptID));
$PAGE->set_url ($url);
$PAGE->set_title (codiana_string ('title:edit_attempt'));
$PAGE->set_heading (codiana_create_page_title ($codiana, 'title:edit_attempt'));
$PAGE->set_pagelayout ('standard');
$output = $PAGE->get_renderer ('mod_codiana');
$output->init ($codiana, $cm, $context, $course);
global $OUTPUT;




//# ----- OUTPUT ----------------------------------------------------------------

$attempt = codiana_get_attempt ($codiana, $attemptID);
$message = null;
if ($edit) {
    $attempt->resultfinal = optional_param ('resultfinal', 0, PARAM_INT);
    $attempt->resultnote  = optional_param ('resultnote', 0, PARAM_TEXT);
    $attempt->state       = optional_param ('state', 0, PARAM_TEXT);
    $result               = codiana_set_attempt ($attempt);

    if ($result)
        $message = codiana_message::create ('attempt_edited');
    else
        $message = codiana_message::create ('attempt_edit_failed');
}

echo $OUTPUT->header ();

if ($message) $message->renderAll ();
echo $output->view_edit_result ($attempt);

echo $OUTPUT->footer ();