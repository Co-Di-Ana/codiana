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

// TODO insert into queue after input exists?
// TODO rename after activation/open Warning
// TODO grade.php uzivatel permission
// TODO schovat cestu v error hlašení

// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id ();

// check login and grap context
require_login ($course, false, $cm);

// ----- CAPABILITY managetaskfiles ----------------------------------------------------------------
$context = context_module::instance ($cm->id);
require_capability ('mod/codiana:managetaskfiles', $context);

// clean-up URL
$url = new moodle_url('/mod/codiana/managefiles.php', array ('id' => $cm->id));
$PAGE->set_url ($url);
$PAGE->set_title (codiana_string ('title:manage_files'));
$PAGE->set_heading (codiana_create_page_title($codiana, 'title:manage_files'));
$PAGE->set_pagelayout ('standard');
$output = $PAGE->get_renderer ('mod_codiana');
$output->init ($codiana, $cm, $context, $course);
global $OUTPUT;



echo $OUTPUT->header ();
echo html_writer::tag ('h2', codiana_string ('title:managefilex', $codiana->name));
$mform = new mod_codiana_managefiles_form ($codiana, $url);


// Form processing and displaying
if ($mform->is_cancelled ()) {
    // form canceled
} else if ($data = $mform->get_data ()) {
    // form is valid, now file check
    // file extension support check
    $messages = $mform->validateFiles ();
    echo codiana_message::renderItems ($messages, codiana_message::create ('noactionperformed', 'error'));

    $mform->display ($codiana, $cm, $course);
} else {
    // show form
    $mform->display ($codiana, $cm, $course);
}


echo $OUTPUT->footer ();