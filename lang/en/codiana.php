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

defined ('MOODLE_INTERNAL') || die();

$string['modulename']           = 'codiana';
$string['modulenameplural']     = 'codiana';
$string['modulename_help']      = 'Use the codiana module for code diagnosis.';
$string['codiananame']          = 'codiana name';
$string['codiananame_help']     = 'This is the content of the help tooltip associated with the codiananame field. Markdown syntax is supported.';
$string['codiana']              = 'codiana';
$string['pluginadministration'] = 'Codiana administration';
$string['pluginname']           = 'codiana';


// edit form
$string['name']                  = 'Task name';
$string['mainfilename']          = 'Task main file name';
$string['difficulty']            = 'Task Difficulty';
$string['outputmethod']          = 'Output method';
$string['outputmethod:strict']   = 'strict comparison';
$string['outputmethod:tolerant'] = 'tolerant comparison';
$string['outputmethod:vague']    = 'vague comparison';
$string['grademethod']           = 'Grade method';
$string['grademethod:first']     = 'First sent solution';
$string['grademethod:last']      = 'Last sent solution';
$string['grademethod:best']      = 'The best solution sent';
$string['languages']             = 'Allowed languages';
$string['timeopen']              = 'Avaiable from date';
$string['timeclose']             = 'Avaiable to date';
$string['maxusers']              = 'Max users';
$string['maxattempts']           = 'Max attempts';
$string['limittime']             = 'Time limit';
$string['limitmemory']           = 'Memory limit';
$string['solutionfile']          = 'Solution file';
$string['inputfile']             = 'Input file';
$string['outputfile']            = 'Output file';
$string['inputexample']          = 'Input example';
$string['outputexample']         = 'Output example';
$string['measurevalues']         = 'Measure values';
$string['sourcefile']            = 'Solution';
$string['solutionlanguage']      = 'Solution language';
$string['outputorsolutionfile']  = 'Output file or solution file';

// edit form help
$string['name_help']                 = 'Task name help';
$string['mainfilename_help']         = 'Task main file name help';
$string['difficulty_help']           = 'Difficulty help';
$string['outputmethod_help']         = 'Grade method help';
$string['grademethod_help']          = 'Grade method help';
$string['languages_help']            = 'Languages help';
$string['timeopen_help']             = 'Open help';
$string['timeclose_help']            = 'Close help';
$string['maxusers_help']             = 'Max users help';
$string['maxattempts_help']          = 'Max attempts help';
$string['limittime_help']            = 'Task time limit';
$string['limitmemory_help']          = 'Task memory limit';
$string['solutionfile_help']         = 'Solution file help';
$string['inputfile_help']            = 'Input file help';
$string['outputfile_help']           = 'Output file help';
$string['inputexample_help']         = 'Input help';
$string['outputexample_help']        = 'Output help';
$string['measurevalues_help']        = 'If checked, system will automatically measure time and memory values, and will generate output file.';
$string['sourcefile_help']           = 'Solution help';
$string['solutionlanguage_help']     = 'Specify main file extension if zipped';
$string['outputorsolutionfile_help'] = 'Output file help';


// mod_form sections
$string['submitsolution:submit']          = 'Send';
$string['section:availability']           = 'Availabilty';
$string['section:limits']                 = 'Limits';
$string['section:files']                  = 'Files';
$string['section:examples']               = 'I/O examples';
$string['section:results']                = 'View options';
$string['section:inputfilesection']       = 'Input file';
$string['section:outputfilesection']      = 'Output file or solution';
$string['section:inputfilesection_help']  = 'You MUST specify or generate input file.';
$string['section:outputfilesection_help'] = 'You MUST specify or let the system generate output file';



// view
$string['view:mainfilename:warning'] = 'Submitted solution must contain startup file %s with correct extension';


// settings
$string['setting:storage']     = 'Storage';
$string['setting:limittime']   = 'Maximum execution time';
$string['setting:limitmemory'] = 'Maximum memory peak';
$string['setting:islocal']     = 'Local storage';
$string['setting:storagepath'] = 'Data path';
$string['setting:sshusername'] = 'Username (SSH)';
$string['setting:sshpassword'] = 'Password (SSH)';


$string['setting:storage_desc']     = 'Storage setting';
$string['setting:limittime_desc']   = 'Maximum allowed execution time in seconds';
$string['setting:limitmemory_desc'] = 'Maximum memory peak value in MB';
$string['setting:islocal_desc']     = 'Specify whether is storage local, if so, checked this option';
$string['setting:storagepath_desc'] = 'Specify where will be codiana data stored';
$string['setting:sshusername_desc'] = 'If storage is remote, specify username for ssh conenction, otherwise leave it blank';
$string['setting:sshpassword_desc'] = 'If storage is remote, specify password for ssh conenction, otherwise leave it blank';


// states
$string['state:process_aborted']     = 'Solution processing aborted';
$string['state:other_error']         = 'Other error';
$string['state:waiting_to_process']  = 'Waiting to process';
$string['state:code_dangerous']      = 'Dangerous code';
$string['state:code_invalid']        = 'Invalid code';
$string['state:code_valid']          = 'Valid code';
$string['state:comiplation_error']   = 'Compilation error';
$string['state:comiplation_timeout'] = 'Compilation timeout';
$string['state:comiplation_ok']      = 'Compilation ok';
$string['state:execution_error']     = 'Execution error';
$string['state:execution_timeout']   = 'Execution timeout';
$string['state:execution_ok']        = 'Execution ok';
$string['state:output_error']        = 'Outpu error';
$string['state:time_error']          = 'Time error';
$string['state:memory_error']        = 'Memory Error';
$string['state:measurement_ok']      = 'Measurement ok';




$string['abortedsolution:warning'] = 'Your previous attempt was not yet processed. By submitting new solution will abort your previous solution';


// error
$string['error:filedoesnotexists'] = "File you're looking for does't exists!";


// capabilities
$string['addinstance']         = 'Add a new codiana';
$string['createinputfile']     = 'Upload input file';
$string['manager']             = 'Have higher permissions';
$string['managetaskfiles']     = 'Manage task files';
$string['submitprotosolution'] = 'Submit prototype solution';
$string['submitsolution']      = 'Submit solution';
$string['viewmyattempts']      = 'View ones attempts';
$string['viewresults']         = 'View all results';


$string['downloadoutput'] = 'Download current output';
$string['downloadinput']  = 'Download current input';




$string['managefiles:outputorsolution'] = 'Output file or solution';
$string['managefiles:output']           = 'Output file';
$string['managefiles:solution']         = 'Solution';


$string['message:protoinsertedintoqueue']  = 'Solution inserted into queue, measured values will be autimatically in task setting criteria';
$string['message:errorinsertingintoqueue'] = 'Error inserting into queue';
$string['message:noactionperformed']       = 'No action performed!';
$string['message:cannotdeleteinputfile']   = 'Cannot delete input file';
$string['message:cannotcreateinputfile']   = 'Cannot create input file';
$string['message:inputsaved']              = 'Input file was successfully saved';
$string['message:inputgenerated']          = 'Input file was successfully generated';
$string['message:errorgeneratinginput']    = 'Error while generating input file';
$string['message:cannotdeleteoutputfile']  = 'Cannot delete output file';
$string['message:cannotcreateoutputfile']  = 'Cannot create output file';
$string['message:outputsaved']             = 'Output file was successfully saved';
$string['message:fileistoobig']            = 'Generated file is too large!';
$string['message:filegenerated']           = 'Input file was successfully generated!';


$string['date:justnow']     = 'just now';
$string['date:xminutesago'] = '%d minutes ago';
$string['date:hourago']     = 'hour ago';
$string['date:xhoursago']   = '%d hours ago';
$string['date:yesterday']   = 'yesterday';
$string['date:xdaysago']    = '%d days ago';



