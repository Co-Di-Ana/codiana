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

// grab course module id
$id = optional_param ('id', 0, PARAM_INT);
$userid = optional_param ('userid', 0, PARAM_INT);
$ordinal = optional_param ('ordinal', 0, PARAM_INT);

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
$context = context_module::instance ($cm->id);

// Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
if ($context->contextlevel != CONTEXT_MODULE) {
    print_error('codiana:error:youcannotdownloadthisfile', 'codiana');
}

// check login and grap context
require_login ($course, false, $cm);


// clean-up URL
$url = new moodle_url(
    '/mod/codiana/sourcecode.php',
    array (
          'id' => $cm->id,
          'userid' => $userid,
          'ordinal' => $ordinal
    )
);

// TODO create function
$isOpen = codiana_is_task_open ($codiana);
$isSolver = $USER->id == $userid;
$shift = codiana_get_task_state ($isOpen, $isSolver);
$settings = $codiana->settings;
$codeIndex = array_search ('code', codiana_display_options::$fields) * codiana_display_options::COUNT;
$codeIndex += $shift;
$hasPermission = $settings & (1 << $codeIndex);


// throw error if user doesn't have sufficient permissions
if (!$hasPermission)
    print_error('codiana:error:youcannotdownloadthisfile', 'codiana');


// TODO change of global config path may result into moving files to new location
$dataDir = get_config ('codiana', 'storagepath');
$attemptName = sprintf ('attempt-%03d', $ordinal);
$codianaName = sprintf ('task-%04d', $codiana->id);
$userName = sprintf ('user-%05d', $userid);


// grap file transfer
// TODO add remote support
$fileTransfer = codiana_get_file_transfer ();
$content = $fileTransfer->loadFile ("$dataDir/$codianaName/$userName/prev/$attemptName.zip");

// create tmp file and write result
$tmpFile = tmpfile ();
fwrite ($tmpFile, $content);

// finally, send file to user
$info = stream_get_meta_data ($tmpFile);
$uri = $info['uri'];
send_file ($uri, "$attemptName.zip");