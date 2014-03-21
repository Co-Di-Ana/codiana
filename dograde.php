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
require_once ($CFG->libdir . '/gradelib.php');

global $DB, $USER;

// grab course, context and codiana instance
list($cm, $course, $codiana) = codiana_get_from_id ();

require_login ($course, true, $cm);
$context = context_module::instance ($cm->id);


// array of graded users
$userids = @$_POST['userid'];

if (!is_array ($userids) || empty ($userids))
    redirect (new moodle_url('/mod/codiana/grade.php', array ('id' => $cm->id)), codiana_string ('message:nochangespermormed'));

$result = array ();
foreach ($userids as $userid) {
    $grade    = array ('userid' => $userid, 'rawgrade' => intval ($_POST["grade_$userid"]));
    $result[] = grade_update ('mod/codiana', $course->id, 'mod', 'codiana', $codiana->id, 0, $grade);
}

$total = count ($result);
redirect (new moodle_url('/mod/codiana/grade.php', array ('id' => $cm->id)), codiana_string ('message:xchangesperformed', $total));