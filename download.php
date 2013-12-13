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
$type = required_param ('type', PARAM_ALPHA);

if ($type != 'i' && $type != 'o')
    print_error ('invalidcoursemodule');

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

$context = context_module::instance ($cm->id);



// Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
if ($context->contextlevel != CONTEXT_MODULE) {
    return false;
}

// check login and grap context
require_login ($course, false, $cm);


// clean-up URL
$url = new moodle_url(
    '/mod/codiana/download.php',
    array (
          'id' => $cm->id,
          'typw' => $type
    )
);


// throw error if user doesn't have sufficient permissions
if (!has_capability ('mod/codiana:managetaskfiles', $context))
    print_error ('codiana:error:youcannotdownloadthisfile', 'codiana');


$tmpFile = tmpfile ();
$info = stream_get_meta_data ($tmpFile);
$tmpFileLocation = $info['uri'];
$inputFileLocation = codiana_get_task_file_path ($codiana, $type == 'i' ? codiana_file_path_type::TASK_INPUT : codiana_file_path_type::TASK_OUTPUT);

// grap file transfer
$files = codiana_get_file_transfer ();
$result = $files->copyFile ($inputFileLocation, $tmpFileLocation);

send_file ($tmpFileLocation, ("$codiana->mainfilename.") . ($type == 'i' ? 'in' : 'out'));