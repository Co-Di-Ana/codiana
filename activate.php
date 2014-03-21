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
$activate = required_param ('activate', PARAM_INT);

// check login and grap context
require_login ($course, false, $cm);

// ----- CAPABILITY submitsolution ----------------------------------------------------------------
$context = context_module::instance ($cm->id);
require_capability ('mod/codiana:manager', $context);

// if we want to activate the task, files must be ok
if ($activate) {
    $files = (object)codiana_get_files_status ($codiana);
    if (!$files->input && !$files->output)
        redirect (new moodle_url('/mod/codiana/managefiles.php', array ('id' => $cm->id)), codiana_string ('warning:iofilesmissing'));

    if (!$files->input)
        redirect (new moodle_url('/mod/codiana/managefiles.php', array ('id' => $cm->id)), codiana_string ('warning:ifilesmissing'));

    if (!$files->output)
        redirect (new moodle_url('/mod/codiana/managefiles.php', array ('id' => $cm->id)), codiana_string ('warning:ofilesmissing'));
}

$codiana->state = $activate ? codiana_task_state::ACTIVE : codiana_task_state::NOT_ACTIVE;
$task           = array ('id' => $codiana->id, 'state' => $codiana->state);
$result         = codiana_set_task ($task);
$message        = $activate ? codiana_string ('message:taskactivated') : codiana_string ('message:taskdeactivated');

redirect (new moodle_url('/mod/codiana/view.php', array ('id' => $cm->id)), $message);