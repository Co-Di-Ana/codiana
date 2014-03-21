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
$id   = optional_param ('id', 0, PARAM_INT);
$type = required_param ('type', PARAM_ALPHA);

if ($type != 'i' && $type != 'o')
    print_error ('invalidcoursemodule');


// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id ();

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
          'id'   => $cm->id,
          'typw' => $type
    )
);

// ----- CAPABILITY managetaskfiles ----------------------------------------------------------------

// throw error if user doesn't have sufficient permissions
$context = context_module::instance ($cm->id);
require_capability ('mod/codiana:managetaskfiles', $context);


$tmpFile         = tmpfile ();
$info            = stream_get_meta_data ($tmpFile);
$tmpFileLocation = $info['uri'];
$fileLocation    = codiana_get_task_file_path ($codiana, $type == 'i' ? codiana_file_path_type::TASK_INPUT : codiana_file_path_type::TASK_OUTPUT);

// grap file transfer
$files = codiana_get_file_transfer ();
if (!$files->exists ($fileLocation))
    print_error ('error:filedoesnotexists', 'codiana');

$content = $files->loadFile ($fileLocation);
$result  = $files->saveFile ($content, $tmpFileLocation);

send_file ($tmpFileLocation, ("$codiana->mainfilename.") . ($type == 'i' ? 'in' : 'out'));