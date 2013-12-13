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
$string['modulename_help'] = 'Use the codiana module for code diagnosis.';
$string['codiananame'] = 'codiana name';
$string['codiananame_help'] = 'This is the content of the help tooltip associated with the codiananame field. Markdown syntax is supported.';
$string['codiana'] = 'codiana';
$string['pluginadministration'] = 'codiana administration';
$string['pluginname'] = 'codiana';


// edit form
$string['codiana:name'] = 'Task name';
$string['codiana:mainfilename'] = 'Task main file name';
$string['codiana:difficulty'] = 'Task Difficulty';
$string['codiana:outputmethod'] = 'Output method';
$string['codiana:outputmethod:strict'] = 'strict comparison';
$string['codiana:outputmethod:tolerant'] = 'tolerant comparison';
$string['codiana:outputmethod:vague'] = 'vague comparison';
$string['codiana:grademethod'] = 'Grade method';
$string['codiana:grademethod:first'] = 'First sent solution';
$string['codiana:grademethod:last'] = 'Last sent solution';
$string['codiana:grademethod:best'] = 'The best solution sent';
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
$string['codiana:inputexample'] = 'Input example';
$string['codiana:outputexample'] = 'Output example';
$string['codiana:measurevalues'] = 'Measure values';

$string['codiana:sourcefile'] = 'Solution';
$string['codiana:solutionlanguage'] = 'Solution language';

$string['codiana:outputorsolutionfile'] = 'Output file or solution file';

// edit form help
$string['codiana:name_help'] = 'Task name help';
$string['codiana:mainfilename_help'] = 'Task main file name help';
$string['codiana:difficulty_help'] = 'Difficulty help';
$string['codiana:outputmethod_help'] = 'Grade method help';
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
$string['codiana:inputexample_help'] = 'Input help';
$string['codiana:outputexample_help'] = 'Output help';
$string['codiana:measurevalues_help'] = 'If checked, system will automatically measure time and memory values, and will generate output file.';

$string['codiana:sourcefile_help'] = 'Solution help';
$string['codiana:solutionlanguage_help'] = 'Specify main file extension if zipped';

$string['codiana:outputorsolutionfile_help'] = 'Output file help';


// mod_form sections
$string['codiana:submitsolution:submit'] = 'Send';
$string['codiana:section:availability'] = 'Availabilty';
$string['codiana:section:limits'] = 'Limits';
$string['codiana:section:files'] = 'Files';
$string['codiana:section:examples'] = 'I/O examples';
$string['codiana:section:results'] = 'View options';
$string['codiana:section:inputfilesection'] = 'Input file';
$string['codiana:section:outputfilesection'] = 'Output file or solution';

$string['codiana:section:inputfilesection_help'] = 'You MUST specify or generate input file.';
$string['codiana:section:outputfilesection_help'] = 'You MUST specify or let the system generate output file';



// view
$string['codiana:view:mainfilename:warning'] = 'Submitted solution must contain startup file %s with correct extension';


// settings
$string['codiana:setting:storage'] = 'Storage';
$string['codiana:setting:limittime'] = 'Maximum execution time';
$string['codiana:setting:limitmemory'] = 'Maximum memory peak';
$string['codiana:setting:islocal'] = 'Local storage';
$string['codiana:setting:storagepath'] = 'Data path';
$string['codiana:setting:sshusername'] = 'Username (SSH)';
$string['codiana:setting:sshpassword'] = 'Password (SSH)';


$string['codiana:setting:storage_desc'] = 'Storage setting';
$string['codiana:setting:limittime_desc'] = 'Maximum allowed execution time in seconds';
$string['codiana:setting:limitmemory_desc'] = 'Maximum memory peak value in MB';
$string['codiana:setting:islocal_desc'] = 'Specify whether is storage local, if so, checked this option';
$string['codiana:setting:storagepath_desc'] = 'Specify where will be codiana data stored';
$string['codiana:setting:sshusername_desc'] = 'If storage is remote, specify username for ssh conenction, otherwise leave it blank';
$string['codiana:setting:sshpassword_desc'] = 'If storage is remote, specify password for ssh conenction, otherwise leave it blank';


// states
$string['codiana:state:waitingtoprocess'] = 'Waiting to process';
$string['codiana:state:correctsolution'] = 'Correct solution';
$string['codiana:state:wrongsolution'] = 'Wrong solution';
$string['codiana:state:maxtimelimit'] = 'Reached maximum time limit';
$string['codiana:state:maxmemorylimit'] = 'Reached maximum memory limit';
$string['codiana:state:compilationerror'] = 'Compilation error';
$string['codiana:state:runerror'] = 'Run error';
$string['codiana:state:looperror'] = 'Solution looped';
$string['codiana:state:dangerouscode'] = 'Dangerous code';
$string['codiana:state:nomainclass'] = 'Cannot locate main class';
$string['codiana:state:aborted'] = 'Solution processing aborted';
$string['codiana:state:unkwnownerror'] = 'Unknown error';


$string['codiana:abortedsolution:warning'] = 'Your previous attempt was not yet processed. By submitting new solution will abort your previous solution';


// error
$string['codiana:error:youcannotdownloadthisfile'] = "You don't have sufficient permissions to download this file!";
$string['codiana:error:filedoesnotexists'] = "File you're looking for does't exists!";


// capabilities
$string['codiana:addinstance'] = 'Add a new codiana';
$string['codiana:createinputfile'] = 'Upload input file';
$string['codiana:manager'] = 'Have higher permissions';
$string['codiana:managetaskfiles'] = 'Manage task files';
$string['codiana:submitprotosolution'] = 'Submit prototype solution';
$string['codiana:submitsolution'] = 'Submit solution';
$string['codiana:viewmyattempts'] = 'View ones attempts';
$string['codiana:viewresults'] = 'View all results';


$string['codiana:downloadoutput'] = 'Download current output';
$string['codiana:downloadinput'] = 'Download current input';




$string['codiana:managefiles:outputorsolution'] = 'Output file or solution';
$string['codiana:managefiles:output'] = 'Output file';
$string['codiana:managefiles:solution'] = 'Solution';


$string['codiana:message:protoinsertedintoqueue'] = 'Solution inserted into queue, measured values will be autimatically in task setting criteria';
$string['codiana:message:errorinsertingintoqueue'] = 'Error inserting into queue';
$string['codiana:message:noactionperformed'] = 'No action performed!';
$string['codiana:message:cannotdeleteinputfile'] = 'Cannot delete input file';
$string['codiana:message:cannotcreateinputfile'] = 'Cannot create input file';
$string['codiana:message:inputsaved'] = 'Input file was successfully saved';
$string['codiana:message:inputgenerated'] = 'Input file was successfully generated';
$string['codiana:message:errorgeneratinginput'] = 'Error while generating input file';
$string['codiana:message:cannotdeleteoutputfile'] = 'Cannot delete output file';
$string['codiana:message:cannotcreateoutputfile'] = 'Cannot create output file';
$string['codiana:message:outputsaved'] = 'Output file was successfully saved';
$string['codiana:message:fileistoobig'] = 'Generated file is too large!';
$string['codiana:message:filegenerated'] = 'Input file was successfully generated!';



