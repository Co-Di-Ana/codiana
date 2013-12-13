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
 * Internal library of functions for module codiana
 *
 * All the codiana specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_codiana
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined ('MOODLE_INTERNAL') || die();

/**
 * Class codiana_state, helper class for task states
 */
class codiana_state {

    /** @var string */
    const PREFIX = 'codiana:state:';

    const WAITINGTOPROCESS = 0;

    const CORRECTSOLUTION = 1;

    const WRONGSOLUTION = 2;

    const MAXTIMELIMIT = 3;

    const MAXMEMORYLIMIT = 4;

    const COMPILATIONERROR = 5;

    const RUNERROR = 6;

    const LOOPERROR = 7;

    const DANGEROUSCODE = 8;

    const NOMAINCLASS = 9;

    const ABORTED = 10;

    const UNKWNOWNERROR = '?';

    private static $state = array (
        codiana_state::WAITINGTOPROCESS => 'waitingtoprocess',
        codiana_state::CORRECTSOLUTION => 'correctsolution',
        codiana_state::WRONGSOLUTION => 'wrongsolution',
        codiana_state::MAXTIMELIMIT => 'maxtimelimit',
        codiana_state::MAXMEMORYLIMIT => 'maxmemorylimit',
        codiana_state::COMPILATIONERROR => 'compilationerror',
        codiana_state::RUNERROR => 'runerror',
        codiana_state::LOOPERROR => 'looperror',
        codiana_state::DANGEROUSCODE => 'dangerouscode',
        codiana_state::NOMAINCLASS => 'nomainclass',
        codiana_state::ABORTED => 'aborted',
        codiana_state::UNKWNOWNERROR => 'unkwnownerror'
    );



    /**
     * @param $value int state index
     * @return string state name
     */
    public static function get ($value) {
        return get_string (codiana_state::PREFIX .
                           (array_key_exists ($value, codiana_state::$state) ?
                               codiana_state::$state[$value] :
                               codiana_state::$state['?']
                           ), 'codiana');
    }
}



/**
 * Class codiana_display_options, helper class for display options values,
 * State of task attampt
 */
class codiana_display_options {

    const OPEN_SOLVER = 0;

    const CLOSE_SOLVER = 1;

    const OPEN_OTHERS = 2;

    const CLOSE_OTHERS = 3;

    const COUNT = 4;

    /** @var array of all display quadrants */
    public static $types = array (
        codiana_display_options::OPEN_SOLVER,
        codiana_display_options::CLOSE_SOLVER,
        codiana_display_options::OPEN_OTHERS,
        codiana_display_options::CLOSE_OTHERS);

    /**
     * @var array of all fields to edit
     */
    public static $fields = array ('basestat', 'state', 'runstat', 'resultstat', 'code');
}



/**
 * Class codiana_grade_method, helper class for grade method values.
 * Which solution choose as main for grading
 */
class codiana_grade_method {

    const SOLUTION_FIRST = 0;

    const SOLUTION_LAST = 1;

    const SOLUTION_BEST = 2;

//    const SOLUTION_MEAN = 3;

    /** @var array of all grade methods */
    public static $types = array (
        codiana_grade_method::SOLUTION_FIRST => 'codiana:grademethod:first',
        codiana_grade_method::SOLUTION_LAST => 'codiana:grademethod:last',
        codiana_grade_method::SOLUTION_BEST => 'codiana:grademethod:best',
//        codiana_grade_method::SOLUTION_MEAN => 'codiana:grademethod:mean'
    );
}



/**
 * Class codiana_output_method, helper class for output method values
 * How to grade output
 */
class codiana_output_method {

    const GRADE_STRICT = 0;

    const GRADE_TOLERANT = 1;

    const GRADE_VAGUE = 2;

    /** @var array of all output methods */
    public static $types = array (
        codiana_output_method::GRADE_STRICT => 'codiana:outputmethod:strict',
        codiana_output_method::GRADE_TOLERANT => 'codiana:outputmethod:tolerant',
        codiana_output_method::GRADE_VAGUE => 'codiana:outputmethod:vague'
    );
}



class codiana_queue_type {

    const SOLUTION_CHECK = 1;

    const PROTO_CHECK = 2;

    const PLAGIARISM_CHECK = 3;
}



/**
 * try to get $prop value from $codiana object and convert it to number
 * @param stdClass $codiana
 * @param string $prop
 * @param bool $required
 * @return bool success/failure 0 is return false
 */
function codiana_check_int ($codiana, $prop, $required = true) {
    if (!isset ($codiana->$prop)) {
        if ($required) {
            return false;
        } else {
            $codiana->$prop = null;
            return true;
        }
    }
    if (intval ($codiana->$prop) == 0)
        return false;
    return true;
}

/**
 * @param $value string value
 * @param null $default string default value, if $value is empty
 * @return null|string string trimmed value or null/default value
 */
function codiana_parse_string ($value, $default = null) {
    $value = empty($value) ? $default : trim ($value);
    return $value;
}

/**
 * @param $value int value
 * @param null $default int default value, if $value is empty
 * @return int|null int value format or null/default value
 */
function codiana_parse_int ($value, $default = null) {
    $value = empty($value) ? $default : intval ($value);
    return $value;
}

/**
 * @param $value int value
 * @param null $default int default value, if $value is empty
 * @return int|null timestamp in int format or null/default value
 */
function codiana_parse_timestamp ($value, $default = null) {
    $value = empty($value) ? $default : intval ($value);
    return $value;
}

function codiana_solution_submit () {
//    global $USER;
//    echo '<pre>';
//    print_r ($USER);
//    die ();
}

/**
 * @param $mform mixed
 * @param $name string name of input element with file
 * @return string file extension
 */
function codiana_solution_get_extension ($mform, $name) {
    $filename = $mform->get_new_filename ($name);
    $extension = pathinfo ($filename, PATHINFO_EXTENSION);

    return $extension;
}


/**
 * @return IFileTransfer instance of either LocalFileTransfer or RemoteFileTransfer
 * (depends on 'codiana/islocal' config value )
 */
function codiana_get_file_transfer () {
    global $CFG;
    require_once $CFG->dirroot . '/mod/codiana/filelib.php';

    $isLocal = get_config ('codiana', 'islocal');

    if ($isLocal)
        return new LocalFileTransfer();

    $remote = new RemoteFileTransfer();
    $remote->setConfig (
        array (
              'username' => get_config ('codiana', 'sshusername'),
              'password' => get_config ('codiana', 'sshpassword'),
        )
    );
    return $remote;
}

/**
 * @return array of supported languages, key is language extension, value is language name
 */
function codiana_get_supported_languages () {
    global $DB;
    $result = $DB->get_records ('codiana_language', null, '', 'extension,name');
    $languages = array ();
    foreach ($result as $language)
        $languages[$language->extension] = $language->name;

    return $languages;
}

/**
 * @param $task stdClass task object
 * @return array all registered languages for given task
 * in array key is language extension, value is language name
 */
function codiana_get_task_languages ($task) {
    global $DB;
    $result = trim ($DB->get_field ('codiana', 'languages', array ('id' => $task->id), MUST_EXIST));
    $result = empty($result) ? array () : explode (',', $result);

    // empty result
    if (sizeof ($result) == 0)
        return array ();

    // get all allowed languages
    $allLanguages = codiana_get_supported_languages ();
    $languages = array ();
    foreach ($result as $extension) {
        if (array_key_exists ($extension, $allLanguages))
            $languages[$extension] = $allLanguages[$extension];
    }

    return $languages;
}

/**
 * @param $task stdClass task object
 * @param $userID int user id
 * @param $order string valid order by expression (ORDER BY $order)
 * @param $fields array which field will be selected
 * @return array of all users attempts, id is key, value is attempt
 */
function codiana_get_all_attempts ($task, $userID, $fields = array (), $order = 'timesent DESC') {
    global $DB;
    $fields = implode (",", $fields);
    $result = $DB->get_records_sql (
        "SELECT $fields
        FROM {codiana_attempt} attempt
        LEFT JOIN {user} user ON
              (user.id = attempt.userid)
        WHERE
              (userid = :userid AND taskid = :taskid)
        ORDER BY
              $order",
        array ('userid' => $userID, 'taskid' => $task->id),
        IGNORE_MISSING
    );
    return $result;
}


/**
 * @param $task stdClass task object
 * @param $userID id of a user to show attempt
 * @param $fields array which field will be selected
 * @return stdClass of result for given student having one or more attempt
 * based on $task settitng, will return SOLUTION_LAST OR SOLUTION_FIRST or SOLUTION_BEST solutions
 * @throws Exception when task is wrongly configured
 */
function codiana_get_grade_attempt ($task, $userID, $fields = array ()) {
    global $DB;
    $order =
        ($task->grademethod == codiana_grade_method::SOLUTION_LAST ? 'timesent desc' :
            ($task->grademethod == codiana_grade_method::SOLUTION_FIRST ? 'timesent asc' :
                ($task->grademethod == codiana_grade_method::SOLUTION_BEST ? 'resultfinal desc' : NULL)
            )
        );

    if (is_null ($order))
        throw new Exception ('wrong task setting');

    $fields = implode (",", $fields);

    $result = $DB->get_record_sql (
        "SELECT $fields
        FROM
              {codiana_attempt} attempt
        LEFT JOIN {user} user ON
              (user.id = attempt.userid)
        WHERE
              (userid = :userid AND taskid = :taskid)
        ORDER BY
              $order
        LIMIT 1",
        array ('userid' => $userID, 'taskid' => $task->id),
        IGNORE_MISSING
    );
    return $result;
}

/**
 * @param $task stdClass task object
 * @return array of result for each student having one or more attempt one row
 * based on $task settitng, will return SOLUTION_LAST OR SOLUTION_FIRST or SOLUTION_BEST solutions
 * array where id is key, value is attempt
 * @throws Exception when task is wrongly configured
 */
function codiana_get_grade_attempts ($task) {
    global $DB;
    $order =
        ($task->grademethod == codiana_grade_method::SOLUTION_LAST ? 'timesent desc' :
            ($task->grademethod == codiana_grade_method::SOLUTION_FIRST ? 'timesent asc' :
                ($task->grademethod == codiana_grade_method::SOLUTION_BEST ? 'resultfinal desc' : NULL)
            )
        );

    echo $order . "<br />";
    if (is_null ($order))
        throw new Exception ('wrong task setting');

    $result = $DB->get_records_sql (
        "SELECT *
        FROM {codiana_attempt}
        WHERE taskid = :taskid
        GROUP BY userid
        ORDER BY $order, resultfinal DESC",
        array ('taskid' => $task->id),
        IGNORE_MISSING
    );
    return $result;
}

/**
 * @param $array array to translate
 * @return array copy of array where values are 'get_string'ed
 */
function codiana_get_strings_from_array ($array) {
    $result = array ();
    foreach ($array as $key => $value)
        $result[$key] = get_string ($value, 'codiana');
    return $result;
}


/**
 * @param $task stdClass task object
 * @param $time int default time value, if null, current time will be used
 * @return bool wheter is task in open state (depends on time)
 */
function codiana_is_task_open ($task, $time = null) {
    $time = $time == null ? time () : intval ($time);
    $open = $task->timeopen;
    $close = $task->timeclose;

    if (is_null ($open) && is_null ($close))
        return true;

    if (is_null ($open) && $time <= $close)
        return true;

    if (is_null ($close) && $time >= $open)
        return true;

    if ($time >= $open && $time <= $close)
        return true;

    return false;
}

/**
 * @param $userid int user id
 * @return bool if current user is also solver
 */
function codiana_is_task_solver ($userid) {
    global $USER;
    return $userid == $USER->id;
}

/**
 * @param $isOpen bool whether is task in open state
 * @param $isSolver bool wheter is current user solver
 * @return int mask/shift if this task state, e.g. codiana_display_options::OPEN_SOLVER
 */
function codiana_get_task_state ($isOpen, $isSolver) {
    return $isOpen ?
        ($isSolver ? codiana_display_options::OPEN_SOLVER : codiana_display_options::OPEN_OTHERS) :
        ($isSolver ? codiana_display_options::CLOSE_SOLVER : codiana_display_options::CLOSE_OTHERS);
}

/**
 * @param $task stdClass task
 * @param $mask int task state based on time and user
 * @return array of allowed fields groups/fields
 */
function codiana_get_task_fields ($task, $mask) {
    $setting = $task->settings;
    $fields = array ();
    $i = $mask;
    foreach (codiana_display_options::$fields as $field) {
        if ($setting & (1 << $i))
            $fields[] = $field;
        $i += codiana_display_options::COUNT;
    }
    return $fields;
}

/**
 * @param $fields array field groups
 * @return array expanded mysql fields
 */
function codiana_expand_fields ($fields) {
    $result = array ();
    foreach ($fields as $field) {
        switch ($field) {
            case 'basestat':
                $result['id'] = 'attempt.id';
                $result['username'] = "CONCAT_WS (' ', UPPER(user.lastname), user.firstname) AS username";
                $result['timesent'] = "attempt.timesent";
                $result['ordinal'] = "attempt.ordinal";
                break;
            case 'runstat':
                $result['runtime'] = 'attempt.runtime';
                $result['runmemory'] = 'attempt.runmemory';
                $result['runoutput'] = 'attempt.runoutput';
                break;
            case 'resultstat':
                $result['resulttime'] = 'attempt.resulttime';
                $result['resultmemory'] = 'attempt.resultmemory';
                $result['resultoutput'] = 'attempt.resultoutput';
                $result['resultfinal'] = 'attempt.resultfinal';
                break;

            case 'code':
                $result['code'] = 'attempt.ordinal AS code';
                $result['taskid'] = 'attempt.taskid';
                $result['userid'] = 'attempt.userid';
                break;

            case 'state':
                $result['state'] = 'state';
                break;


            default:
                // ignore unknown fields
                break;
        }
    }
    return $result;
}

/**
 * @param $task
 * @param $userID
 * @return mixed|bool false if no attemps int representing last task state
 */
function codiana_get_last_attempt ($task, $userID) {
    global $DB;
    return $DB->get_record_sql (
        "SELECT id,state
        FROM
              {codiana_attempt}
        WHERE
              (userid = :userid AND taskid = :taskid)
        ORDER BY
              ordinal DESC
        LIMIT 1",
        array ('userid' => $userID, 'taskid' => $task->id),
        IGNORE_MISSING
    );
}



class codiana_file_path_type {

    const TASK_INPUT = 1;

    const TASK_OUTPUT = 2;

    const TASK_DATA = 3;

    const USER_SOLUTION = 5;

    const USER_PREVIOUS_FOLDER = 6;

    const USER_CURRENT_FOLDER = 7;

    const USER_PREVIOUS_ZIP = 8;
}



class codiana_output_type {

    const OUTPUT_FILE = 1;

    const SOLUTION = 2;
}



function codiana_get_task_file_path ($task, $type) {
    $dataDir = get_config ('codiana', 'storagepath');
    $codianaID = sprintf ('task-%04d', $task->id);
    $mainfilename = $task->mainfilename;
    switch ($type) {
        case codiana_file_path_type::TASK_INPUT:
            return $dataDir . "$codianaID/data/$mainfilename.in";
        case codiana_file_path_type::TASK_OUTPUT:
            return $dataDir . "$codianaID/data/$mainfilename.out";
        case codiana_file_path_type::TASK_DATA:
            return $dataDir . "$codianaID/data/";
    }
}

function codiana_get_user_file_path ($task, $type, $id, $detail = null) {
    $dataDir = get_config ('codiana', 'storagepath');
    $codianaID = sprintf ('task-%04d', $task->id);
    $userID = sprintf ('user-%04d', $id);
    $mainfilename = $task->mainfilename;

    switch ($type) {
        case codiana_file_path_type::USER_SOLUTION:
            return $dataDir . "$codianaID/$userID/curr/$mainfilename.$detail";
        case codiana_file_path_type::USER_PREVIOUS_FOLDER:
            return $dataDir . "$codianaID/$userID/prev/";
        case codiana_file_path_type::USER_CURRENT_FOLDER:
            return $dataDir . "$codianaID/$userID/curr/";
        case codiana_file_path_type::USER_PREVIOUS_ZIP:
            $attempt = sprintf ('attempt-%04d.zip', $detail);
            return $dataDir . "$codianaID/$userID/prev/$attempt";
    }
}

function codiana_get_files_status ($task) {
    $result = array ();
    $transfer = codiana_get_file_transfer ();
    $result['input'] = $transfer->exists (codiana_get_task_file_path ($task, codiana_file_path_type::TASK_INPUT));
    $result['output'] = $transfer->exists (codiana_get_task_file_path ($task, codiana_file_path_type::TASK_OUTPUT));
    return $result;
}



function codiana_save_user_solution ($task, $userID, $mform, $data) {
    global $DB;

    // process details
    $data = is_object ($data) ? $data : (object)$data;

    // mform constanst
    $content = $mform->get_file_content ($data->elementName);
    $extension = $mform->extension;
    $defaultExtension = $mform->defaultExtension;

    // generate paths
    $currentFolder = codiana_get_user_file_path ($task, codiana_file_path_type::USER_CURRENT_FOLDER, $userID);
    $previousFolder = codiana_get_user_file_path ($task, codiana_file_path_type::USER_PREVIOUS_FOLDER, $userID);
    $solutionFile = codiana_get_user_file_path ($task, codiana_file_path_type::USER_SOLUTION, $userID, $defaultExtension);
    $zippedFile = codiana_get_user_file_path ($task, codiana_file_path_type::USER_PREVIOUS_ZIP, $userID, $data->attempt);

    // grap codiana_get_file_transfer object
    $fileTransfer = codiana_get_file_transfer ();

    // delete old solution and recreate folder
    if (!$fileTransfer->deleteDir ($currentFolder) && $fileTransfer->exists ($currentFolder))
        throw new moodle_exception ('codiana:error:cannotdeletefolder', 'codiana');
    if ((!$fileTransfer->mkDir ($currentFolder) && !$fileTransfer->exists ($currentFolder)) ||
        (!$fileTransfer->mkDir ($previousFolder) && !$fileTransfer->exists ($previousFolder))
    ) {
        throw new moodle_exception ('codiana:error:cannotcreatefolder', 'codiana');
    }

    // save new solution
    if (!$fileTransfer->saveFile ($content, $solutionFile))
        throw new moodle_exception ('codiana:error:cannotsavesolution', 'codiana');

    // if zipped solution, unzip to location and delete zip file
    if ($defaultExtension == 'zip') {
        if (!$fileTransfer->unzip ($solutionFile, $currentFolder))
            throw new moodle_exception ('codiana:error:cannotunzipsolution', 'codiana');
        if (!$fileTransfer->deleteFile ($solutionFile))
            throw new moodle_exception ('codiana:error:cannotdeletesolution', 'codiana');
    }

    // zip solution
    if (!$fileTransfer->zipDir ($currentFolder, $zippedFile))
        throw new moodle_exception ('codiana:error:cannotzipsolution', 'codiana');

    // TODO use codiana_is_zip comparison, not == 'zip'
    $attempt = array (
        'taskid' => $task->id,
        'userid' => $userID,
        'timesent' => time (),
        'state' => codiana_state::WAITINGTOPROCESS,
        'language' => $extension,
        'detail' => $defaultExtension == 'zip' ? 1 : 0,
        'ordinal' => $data->attempt
    );

    $attemptID = $DB->insert_record (
        'codiana_attempt',
        (object)$attempt,
        true
    );

    $queue = array (
        'taskid' => $task->id,
        'userid' => $userID,
        'attemptid' => $attemptID,
        'type' => $data->type,
        'priority' => $data->priority
    );

    $queueID = $DB->insert_record (
        'codiana_queue',
        (object)$queue,
        true
    );

    return true;
}

function codiana_save_proto_solution ($task, $mform) {
    global $USER, $DB;

    $result = array ();
    $totalAttempts = $DB->count_records ('codiana_attempt', array ('userid' => $USER->id, 'taskid' => $task->id));
    $lastAttempt = codiana_get_last_attempt ($task, $USER->id);
    $warning = $totalAttempts > 0 && $lastAttempt->state == codiana_state::WAITINGTOPROCESS;

    if ($warning) {
        // set state to aborted (last attempt) and delete any users queue items
        $lastAttempt->state = codiana_state::ABORTED;
        $DB->update_record ('codiana_attempt', $lastAttempt);
        $DB->delete_records ('codiana_queue', array ('userid' => $USER->id));
    }


    // save solution
    $data = array (
        'priority' => 100,
        'elementName' => 'outputorsolutionfile',
        'attempt' => $totalAttempts + 1,
        'type' => codiana_queue_type::PROTO_CHECK
    );


    if (codiana_save_user_solution ($task, $USER->id, $mform, $data))
        $result[] = codiana_message::create ('protoinsertedintoqueue', 'info');
    else
        $result[] = codiana_message::create ('errorinsertingintoqueue', 'error');

    return $result;
}

function codiana_save_input_file ($task, $mform) {
    $result = array ();

    // define paths
    $inputPath = codiana_get_task_file_path ($task, codiana_file_path_type::TASK_INPUT);
    $data = codiana_get_task_file_path ($task, codiana_file_path_type::TASK_DATA);

    // grab file transfer
    $files = codiana_get_file_transfer ();
    $files->mkDir ($data);

    //delete previous file
    $success = $files->deleteFile ($inputPath);

    // deletion error
    if (!$success && $files->exists ($inputPath)) {
        $result[] = codiana_message::create ('cannotdeleteinputfile', 'error');
    } else {
        $content = $mform->get_file_content ('inputfile');
        $success = $files->saveFile ($content, $inputPath);

        if (!$success && !$files->exists ($inputPath))
            $result[] = codiana_message::create ('cannotcreateinputfile', 'error');
        else
            $result[] = codiana_message::create ('inputsaved', 'info');
    }
    return $result;
}

function codiana_save_output_file ($task, $mform) {
    $result = array ();

    // define paths
    $files = codiana_get_file_transfer ();
    $outputPath = codiana_get_task_file_path ($task, codiana_file_path_type::TASK_OUTPUT);

    //delete previous file
    $success = $files->deleteFile ($outputPath);

    // deletion error
    if (!$success && $files->exists ($outputPath)) {
        $result[] = codiana_message::create ('cannotdeleteoutputfile', 'error');
    } else {
        $content = $mform->get_file_content ('outputorsolutionfile');
        $success = $files->saveFile ($content, $outputPath);

        if (!$success && !$files->exists ($outputPath))
            $result[] = codiana_message::create ('cannotcreateoutputfile', 'error');
        else
            $result[] = codiana_message::create ('outputsaved', 'info');
    }
    return $result;
}

function codiana_generate_input ($task) {
    $tmpFile = tmpfile ();
    $info = stream_get_meta_data ($tmpFile);
    $tmpFileLocation = $info['uri'];

    $result = codiana_generator_parser::generate ($tmpFile);
    if ($result == true) {
        $files = codiana_get_file_transfer ();
        $inputFileLocation = codiana_get_task_file_path ($task, codiana_file_path_type::TASK_INPUT);

        // delete previous file
        if (!$files->deleteFile ($inputFileLocation) && $files->exists ($inputFileLocation))
            return codiana_message::create ('cannotdeleteinputfile', 'error');

        // copy file
        if (!$files->copyFile ($tmpFileLocation, $inputFileLocation) && !$files->exists ($inputFileLocation))
            return codiana_message::create ('cannotcreateinputfile', 'error');
    }
    fclose ($tmpFile);
    return null;
}



class codiana_message {

    /** @var string */
    public $content;

    /** @var array */
    public $attributes;



    /**
     * @param $content string
     * @param null $class string
     */
    public function __construct ($content, $class = null) {
        $this->attributes = is_null ($class) ? array () : array ('class' => $class);
        $this->content = $content;
    }



    /**
     * Render message
     * @return string li element
     */
    public function render () {
        return html_writer::tag ('li', $this->content, $this->attributes);
    }



    public function renderAll () {
        echo codiana_message::renderItems (array ($this));
    }



    /**
     * Renders all items in array, if empty and $emptyItem is set, render only $emptyItem
     * @param $items array of codiana_message
     * @param null $emptyItem codiana_message
     * @param string $tag list start tag (ul/ol)
     * @return string html
     */
    public static function renderItems ($items, $emptyItem = null, $tag = 'ul') {
        $html = '';
        $html .= html_writer::start_tag ($tag);
        foreach ($items as $item)
            $html .= $item->render ();

        if (empty ($items) && !is_null ($emptyItem))
            $html .= $emptyItem->render ();
        $html .= html_writer::end_tag ($tag);
        return $html;
    }



    /**
     * @param $id string get_string id
     * @param null $class attribute class name
     * @param string $component get_string component
     * @return codiana_message
     */
    public static function create ($id, $class = null, $component = 'codiana') {
        return new codiana_message(get_string ('codiana:message:' . $id, $component), $class);
    }
}