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
 * English strings for codiana
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_codiana
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'codiana';
$string['modulenameplural'] = 'codiana';
$string['modulename_help'] = 'Use the codiana module for... | The codiana module allows...';
$string['codiananame'] = 'codiana name';
$string['codiananame_help'] = 'This is the content of the help tooltip associated with the codiananame field. Markdown syntax is supported.';
$string['codiana'] = 'codiana';
$string['pluginadministration'] = 'codiana administration';
$string['pluginname'] = 'codiana';


// edit form
$string['codiana:taskname'] = 'Task name';
$string['codiana:taskmainfilename'] = 'Task main file name';
$string['codiana:taskdifficulty'] = 'Task Difficulty';
$string['codiana:taskgrademethod'] = 'Grade method';
$string['codiana:taksgrademethod:strict'] = 'strict method';
$string['codiana:taksgrademethod:tolerant'] = 'tolerant method';
$string['codiana:taksgrademethod:vague'] = 'vague method';
$string['codiana:tasklanguages'] = 'Allowed languages';
$string['codiana:tasktimeopen'] = 'Avaiable from date';
$string['codiana:tasktimeclose'] = 'Avaiable to date';
$string['codiana:taskmaxusers'] = 'Max users';
$string['codiana:taskmaxattempts'] = 'Max attempts';
$string['codiana:tasklimittime'] = 'Time limit';
$string['codiana:tasklimitmemory'] = 'Memory limit';
$string['codiana:tasksolutionfile'] = 'Solution file';
$string['codiana:taskinputfile'] = 'Input file';
$string['codiana:taskoutputfile'] = 'Output file';
$string['codiana:taskerrorfile'] = 'Error file';
$string['codiana:taskinputexample'] = 'Input example';
$string['codiana:taskoutputexample'] = 'Output example';

// edit form help
$string['codiana:taskname_help'] = 'Task name help';
$string['codiana:taskmainfilename_help'] = 'Task main file name help';
$string['codiana:taskdifficulty_help'] = 'Difficulty help';
$string['codiana:taskgrademethod_help'] = 'Grade method help';
$string['codiana:tasklanguages_help'] = 'Languages help';
$string['codiana:tasktimeopen_help'] = 'Open help';
$string['codiana:tasktimeclose_help'] = 'Close help';
$string['codiana:taskmaxusers_help'] = 'Max users help';
$string['codiana:taskmaxattempts_help'] = 'Max attempts help';
$string['codiana:tasklimittime_help'] = 'Task time limit';
$string['codiana:tasklimitmemory_help'] = 'Task memory limit';
$string['codiana:tasksolutionfile_help'] = 'Solution file help';
$string['codiana:taskinputfile_help'] = 'Input file help';
$string['codiana:taskoutputfile_help'] = 'Output file help';
$string['codiana:taskerrorfile_help'] = 'Error file help';
$string['codiana:taskinputexample_help'] = 'Input help';
$string['codiana:taskoutputexample_help'] = 'Output help';



// mod_form sections
$string['codiana:submitsolution:submit'] = 'Send';
$string['codiana:section:availability'] = 'Availabilty';
$string['codiana:section:limits'] = 'Limits';
$string['codiana:section:files'] = 'Files';
$string['codiana:section:examples'] = 'I/O examples';

$string['codiana:section:files_help'] = 'Upload either solution or output';



// view
$string['codiana:view:taskmainfilename:warning3'] = 'Submitted solution must contain startup file %s with correct extension (e.g. %s.java, %s.py)';