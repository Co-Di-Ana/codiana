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
$string['modulename_help']      = 'CoDiAna module is able to automatically process received solutions (i.e. source codes created by students) on algorithmic tasks while providing results and statistics and monitor their performance. Another capability of the system is the similarity control of the received solutions serving to the potential plagiarism detection.';
$string['codiana']              = 'codiana';
$string['pluginadministration'] = 'CoDiAna administration';
$string['pluginname']           = 'codiana';


// edit form
$string['name']                  = 'Task name';
$string['mainfilename']          = 'Task main file name';
$string['difficulty']            = 'Task difficulty';
$string['outputmethod']          = 'Output method';
$string['outputmethod:strict']   = 'Strict comparison';
$string['outputmethod:tolerant'] = 'Tolerant comparison';
$string['outputmethod:vague']    = 'Vague comparison';
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
$string['name_help']          = 'Main task name';
$string['mainfilename_help']  = 'Main file name. Students will be submitting solutions containing this file. Name should be CamelCase, starting with upper letter following letters and numbers or uderscore. For example in Java this name will be also main Class name.';
$string['difficulty_help']    = 'Taskdifficulty, 1 means easy and 5 means hard.';
$string['outputmethod_help']  = 'Outputs comparison, e.g. how strict should comparison be. Strict - char by char. Tolerant line by line (ignoring empty lines). Vague token by token (output will be split to nonempty tokens which will be comparated).';
$string['grademethod_help']   = 'Which solution choose as Grading solution when multiple attempt from student';
$string['languages_help']     = 'Allowed programming languages.';
$string['timeopen_help']      = 'From when will be task opened.';
$string['timeclose_help']     = 'When will task be closed.';
$string['maxusers_help']      = 'Maximum users solving this task.';
$string['maxattempts_help']   = 'Maximum attempt from user.';
$string['limittime_help']     = 'Task time limits. First threshold defines 100% time result. Second threshold defines 0% time result. Evaluation between thresholds is computed linearly.';
$string['limitmemory_help']   = 'Task memory limits. First threshold defines 100% memory result. Second threshold defines 0% memory result. Evaluation between thresholds is computed linearly.';
$string['solutionfile_help']  = 'File containing correct solution which will be used to generate output and measure time and memory requirements.';
$string['inputfile_help']     = 'Task output file.';
$string['outputfile_help']    = 'Task input file.';
$string['inputexample_help']  = 'Input example of task (mostly simplified example which demonstrates task behaviour).';
$string['outputexample_help'] = 'Output example specification demonstration (should be based on input example)';
// TODO add checkbox
$string['measurevalues_help']        = 'If checked, system will automatically measure time and memory values, and will generate output file.';
$string['sourcefile_help']           = $string['solutionfile_help'];
$string['solutionlanguage_help']     = 'Specify programming language of the solution.';
$string['outputorsolutionfile_help'] = 'Select whether You\'re uploading output or solution.';


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


// settings
$string['setting:storage']     = 'Storage';
$string['setting:limittime']   = 'Maximum execution time';
$string['setting:limitmemory'] = 'Maximum memory peak';
$string['setting:islocal']     = 'Local storage';
$string['setting:storagepath'] = 'Data path';
$string['setting:sshusername'] = 'Username (SSH)';
$string['setting:sshpassword'] = 'Password (SSH)';
$string['setting:javaip']      = 'Java-side ip';
$string['setting:javaport']    = 'Java-side port';
$string['setting:javamessage'] = 'Message phrase for Java-side app';


$string['setting:storage_desc']     = 'Storage setting';
$string['setting:limittime_desc']   = 'Maximum allowed execution time in seconds';
$string['setting:limitmemory_desc'] = 'Maximum memory peak value in B';
$string['setting:islocal_desc']     = 'Specify whether is storage local, if so, checked this option';
$string['setting:storagepath_desc'] = 'Specify where will be codiana data stored';
$string['setting:sshusername_desc'] = 'If storage is remote, specify username for ssh conenction, otherwise leave it blank';
$string['setting:sshpassword_desc'] = 'If storage is remote, specify password for ssh conenction, otherwise leave it blank';
$string['setting:javaip_desc']      = 'IP address of server where Java-side app is running';
$string['setting:javaport_desc']    = 'Port number on which is Java-side app listening';
$string['setting:javamessage_desc'] = 'Phrase which will trigger update on Java-side server';

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
$string['state:output_error']        = 'Output error';
$string['state:time_error']          = 'Time error';
$string['state:memory_error']        = 'Memory Error';
$string['state:measurement_ok']      = 'Measurement ok';




// error
$string['error:filedoesnotexists']               = "File you're looking for does't exists!";
$string['error:youcannotdownloadthisfile']       = 'You cannot download this file!';
$string['error:youcannotperformplagiarismcheck'] = 'You cannot perform plagiarism check!';
$string['error:solutiondoesnotexists']           = 'Solution does not exists!';
$string['error:unsupportedfiletypex']            = 'Unapproved file type \'%s\'';


// capabilities
$string['addinstance']         = 'Add a new codiana';
$string['createinputfile']     = 'Upload input file';
$string['manager']             = 'Have higher permissions';
$string['managetaskfiles']     = 'Manage task files';
$string['submitprotosolution'] = 'Submit prototype solution';
$string['submitsolution']      = 'Submit solution';
$string['viewmyattempts']      = 'View ones attempts';
$string['viewresults']         = 'View all results';


$string['input_file']  = 'Input file';
$string['output_file'] = 'Output file';


$string['downloadoutput'] = 'Download current output';
$string['downloadinput']  = 'Download current input';


$string['form:specify_atleast_one_file'] = 'You must specify atleast one file';



$string['managefiles:outputorsolution'] = 'Output file or solution';
$string['managefiles:output']           = 'Output file';
$string['managefiles:solution']         = 'Solution';


$string['message:protoinsertedintoqueue']         = 'Solution inserted into queue, measured values will be autimatically in task setting criteria';
$string['message:errorinsertingintoqueue']        = 'Error inserting into queue';
$string['message:noactionperformed']              = 'No action performed!';
$string['message:cannotdeleteinputfile']          = 'Cannot delete input file';
$string['message:cannotcreateinputfile']          = 'Cannot create input file';
$string['message:inputsaved']                     = 'Input file was successfully saved';
$string['message:inputgenerated']                 = 'Input file was successfully generated';
$string['message:errorgeneratinginput']           = 'Error while generating input file';
$string['message:cannotdeleteoutputfile']         = 'Cannot delete output file';
$string['message:cannotcreateoutputfile']         = 'Cannot create output file';
$string['message:outputsaved']                    = 'Output file was successfully saved';
$string['message:fileistoobig']                   = 'Generated file is too large!';
$string['message:filegenerated']                  = 'Input file was successfully generated!';
$string['message:cannotconnecttoserver']          = 'Cannot connect to server!';
$string['message:cannotsendmessagetoserver']      = 'Cannot send message to server!';
$string['message:taskdeactivated']                = 'Task has been deactivated!';
$string['message:taskactivated']                  = 'Task is now active!';
$string['message:taskplagcheckinserted']          = 'Task plagiarism detection inserted into queue';
$string['message:soluitonplagcheckinserted']      = 'Solution plagiarism detection inserted into queue';
$string['message:nochangespermormed']             = 'No changes performed';
$string['message:xchangesperformed']              = ' % d change (s) performed';
$string['message:attempt_edited']                 = 'Attempt edited';
$string['message:attempt_edit_failed']            = 'Attempt edit failed!';
$string['message:generate_input_x']               = 'You can generate input %s';
$string['message:maximum_no_of_attempts_reached'] = 'You have reached limit of maximum possible attempts!';
$string['message:no_more_attempts_x_of_x']        = 'No more attempts (%d / %d)';
$string['message:attempt_x_from_x']               = 'Attempt %d / %s';
$string['message:uploaded_x_attempt']             = 'Uploaded %d. attempt';
$string['message:no_grade_yet']                   = 'No grade yet';
$string['message:x_similar_solutions_x']          = '%d similar solution(s) (%d&nbsp;%%)';
$string['message:no_description']                 = 'No description yet';
$string['message:checked_x']                      = 'Checked %s';

$string['date:justnow']       = 'just now';
$string['date:x_minutes_ago'] = ' %d minutes ago';
$string['date:hour_ago']      = 'hour ago';
$string['date:x_hours_ago']   = ' %d hours ago';
$string['date:yesterday']     = 'yesterday';
$string['date:x_days_ago']    = ' %d days ago';

$string['date:in_few_minutes']  = 'in few minutes';
$string['date:in_x_minutes']    = 'in %d minutes';
$string['date:in_one_hour']     = 'in one hour';
$string['date:in_x_hours']      = 'in %d hours';
$string['date:tomorow']         = 'tomorow';
$string['date:x_days_from_now'] = '%d days from now';

$string['menu:viewallresults'] = 'View all results';
$string['menu:generateinput']  = 'Generate input file';
$string['menu:managefiles']    = 'Manage files';
$string['menu:viewmyresults']  = 'View my results';
$string['menu:submitsolution'] = 'Submit solution';
$string['menu:showgrades']     = 'Show grade';
$string['menu:managaegrades']  = 'Manage grades';
$string['menu:activatetask']   = 'Activate task';
$string['menu:deactivatetask'] = 'Deactivate task';


$string['warning:iofilesmissing']            = 'Input and Output files are missing';
$string['warning:ifilesmissing']             = 'Input file is missing!';
$string['warning:ofilesmissing']             = 'Output file is missing!';
$string['warning:submiting_will_override']   = 'By submitting new file, you will override existing one!';
$string['warning:specify_x_before_activate'] = 'You need to specify %s in order to be able to activate task!';
$string['warning:abortedsolution']           = 'Your previous attempt was not yet processed. By submitting new solution will abort your previous solution';
$string['warning:similarityx']               = 'Similarity of %1.2f!';
$string['warning:main_filename_x']           = 'Submitted solution must contain startup file %s with correct extension';
$string['warning:task_no_start']             = 'Task does not have specified start';
$string['warning:task_no_end']               = 'Task does not have specified end';
$string['warning:no_limittime_set']          = 'No time limit set';
$string['warning:no_grade_attempt']          = 'You have no grade attempt';
$string['warning:no_results']                = 'No results';
$string['warning:no_grade']                  = 'No grade';
$string['warning:plags_result']              = 'Plagiarism results';
$string['warning:languages_notice']          = 'Notice';


$string['plagstate:no_dupes_found']         = 'No duplicates found';
$string['plagstate:dupes_found']            = 'Some solutions are similar';
$string['plagstate:check_not_yet_executed'] = 'Not yet executed';
$string['plagstate:check_aborted']          = 'Check aborted';
$string['plagstate:check_in_progress']      = 'Check in progress';

$string['taskstate:active']     = 'Active';
$string['taskstate:not_active'] = 'Deactivated';

$string['taskstate:task_not_active']  = 'Task is not active';
$string['taskstate:task_not_started'] = 'Task has not started';
$string['taskstate:task_has_ended']   = 'Task is over';

$string['taskdetail:simple']  = 'single file';
$string['taskdetail:complex'] = 'more files';

$string['title:managefilex']         = 'Managing files (%s)';
$string['title:edit_attempt_from_x'] = 'Editting attempt from %s';
$string['title:edit_attempt']        = 'Editting attempt';
$string['title:user_grade']          = 'Grade';
$string['title:task_detail']         = 'Task details';
$string['title:task_advanced']       = 'Advanced';
$string['title:task_limits']         = 'Limits';
$string['title:task_status']         = 'Status';
$string['title:general_info']        = 'General info';
$string['title:grades']              = 'Grades';
$string['title:grading_attempt']     = 'Grading attempt';
$string['title:grading_attempts']    = 'Grading attempts';
$string['title:all_attempts']        = 'All attempts';
$string['title:plags_result']        = 'Plagiarism results';
$string['title:task_x']              = 'Task \'%s\'';
$string['title:task_x_title']        = 'Task \'%s\' - %s';
$string['title:description']         = 'Description';
$string['title:generate_input']      = 'Generating input file';
$string['title:grading']             = 'Grading';
$string['title:manage_files']        = 'Manage files';
$string['title:submit_solution']     = 'Submit solution';
$string['title:view_my_attempts']    = 'My attempts';
$string['title:view_all_attempts']   = 'Attempts';
$string['title:stats']               = 'Statistics';
$string['title:state_stat']          = 'Overall Statistics';
$string['title:resultfinal_stat']    = 'Final result Statistics';
$string['title:time_stat']           = 'Time Statistics';
$string['title:memory_stat']         = 'Memory Statistics';


$string['settings:open_solver']   = 'Active task for solver';
$string['settings:close_solver']  = 'Task is over for solver';
$string['settings:active_others'] = 'Active task for others';
$string['settings:close_others']  = 'Task is over for others';

$string['settings:open_solver_desc']   = 'When task is active, settings for solver';
$string['settings:close_solver_desc']  = 'When task is over, settings for solver';
$string['settings:active_others_desc'] = 'When task is active, settings for others';
$string['settings:close_others_desc']  = 'When task is over, settings for others';


$string['btn:edit']                     = 'Edit';
$string['btn:cancel']                   = 'Cancel';
$string['btn:back']                     = 'Back';
$string['btn:check']                    = 'Check';
$string['btn:check_again']              = 'Check again';
$string['btn:here']                     = 'here';
$string['btn:show_all_results']         = 'Show all results';
$string['btn:show_grades_results']      = 'Show grades results';
$string['btn:detect_dupes']             = 'Detect duplicates';
$string['btn:detect_dupes_in_progress'] = 'Detecting duplicates in progress';
$string['btn:detect_dupes_again']       = 'Detect duplicates again';
$string['btn:no_dupes_found_again']     = 'No duplicates found, check again?';
$string['btn:download']                 = 'download';


$string['col:id']              = 'id';
$string['col:timemodified']    = 'Modified';
$string['col:timecreated']     = 'Created';
$string['col:plagscheckstate'] = 'Plag state';
$string['col:maxattempts']     = 'Max attempts';
$string['col:maxusers']        = 'Max users';
$string['col:limittime']       = 'Time limit';
$string['col:limitmemory']     = 'Memory limit';
$string['col:state']           = 'State';
$string['col:timeopen']        = 'Time open';
$string['col:timeclose']       = 'Time close';
$string['col:timesent']        = 'Time sent';
$string['col:languages']       = 'Languages';
$string['col:difficulty']      = 'Difficulty';
$string['col:outputmethod']    = 'Output method';
$string['col:grademethod']     = 'Grade method';

$string['col:suggested_grade'] = 'Suggested grade';
$string['col:current_grade']   = 'Current grade';
$string['col:finalresult']     = 'Final result';
$string['col:username']        = 'Username';

$string['col:str_long_grade'] = 'Current grade';
$string['col:dategraded']     = 'Graded';

$string['col:resultnote']     = 'Note';
$string['col:userid']         = 'ID';
$string['col:resultfinal']    = 'Final result %';
$string['col:resultoutput']   = 'Output % ';
$string['col:resultmemory']   = 'Memoroy % ';
$string['col:resulttime']     = 'Time % ';
$string['col:ordinal']        = '';
$string['col:taskid']         = 'Task id';
$string['col:language']       = 'Language';
$string['col:detail']         = 'Detail';
$string['col:runtime']        = 'Time';
$string['col:runoutput']      = 'Output';
$string['col:runmemory']      = 'Memoroy';
$string['col:code']           = 'Code';
$string['col:plags']          = 'Plags';
$string['col:plagstimecheck'] = 'Plagiarism check date';

$string['col:first']   = 'First';
$string['col:second']  = 'Second';
$string['col:result']  = 'Result';
$string['col:details'] = 'Note';

$string['legend:memory']      = '%s - %s kB (%s×)';
$string['legend:time']        = '%s - %s ms (%s×)';
$string['legend:resultfinal'] = '%s - %s %% (%s×)';
$string['legend:memory_x']      = '%s kB (%s×)';
$string['legend:time_x']        = '%s ms (%s×)';
$string['legend:resultfinal_x'] = '%s %% (%s×)';