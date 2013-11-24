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
$string['codiana:name'] = 'Task name';
$string['codiana:mainfilename'] = 'Task main file name';
$string['codiana:difficulty'] = 'Task Difficulty';
$string['codiana:grademethod'] = 'Grade method';
$string['codiana:grademethod:strict'] = 'strict method';
$string['codiana:grademethod:tolerant'] = 'tolerant method';
$string['codiana:grademethod:vague'] = 'vague method';
$string['codiana:languages'] = 'Allowed languages';
$string['codiana:timeopen'] = 'Avaiable from date';
$string['codiana:timeclose'] = 'Avaiable to date';
$string['codiana:maxusers'] = 'Max users';
$string['codiana:maxattempts'] = 'Max attempts';
$string['codiana:limittime'] = 'Time limit';
$string['codiana:limitmemory'] = 'Memory limit';
$string['codiana:solutionfile'] = 'Solution file';
$string['codiana:inputfile'] = 'Input file';
$string['codiana:outputfile'] = 'Output file';
$string['codiana:errorfile'] = 'Error file';
$string['codiana:inputexample'] = 'Input example';
$string['codiana:outputexample'] = 'Output example';

$string['codiana:sourcefile'] = 'Solution';
$string['codiana:solutionlanguage'] = 'Solution language';

// edit form help
$string['codiana:name_help'] = 'Task name help';
$string['codiana:mainfilename_help'] = 'Task main file name help';
$string['codiana:difficulty_help'] = 'Difficulty help';
$string['codiana:grademethod_help'] = 'Grade method help';
$string['codiana:languages_help'] = 'Languages help';
$string['codiana:timeopen_help'] = 'Open help';
$string['codiana:timeclose_help'] = 'Close help';
$string['codiana:maxusers_help'] = 'Max users help';
$string['codiana:maxattempts_help'] = 'Max attempts help';
$string['codiana:limittime_help'] = 'Task time limit';
$string['codiana:limitmemory_help'] = 'Task memory limit';
$string['codiana:solutionfile_help'] = 'Solution file help';
$string['codiana:inputfile_help'] = 'Input file help';
$string['codiana:outputfile_help'] = 'Output file help';
$string['codiana:errorfile_help'] = 'Error file help';
$string['codiana:inputexample_help'] = 'Input help';
$string['codiana:outputexample_help'] = 'Output help';


$string['codiana:sourcefile_help'] = 'Solution help';
$string['codiana:solutionlanguage_help'] = 'Specify solution language';



// mod_form sections
$string['codiana:submitsolution:submit'] = 'Send';
$string['codiana:section:availability'] = 'Availabilty';
$string['codiana:section:limits'] = 'Limits';
$string['codiana:section:files'] = 'Files';
$string['codiana:section:examples'] = 'I/O examples';

$string['codiana:section:files_help'] = 'Upload either solution or output';



// view
$string['codiana:view:mainfilename:warning'] = 'Submitted solution must contain startup file %s with correct extension (e.g. %s.java, %s.py)';