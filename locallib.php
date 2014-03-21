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
define ('CODIANA_ALL_USERS_ID', 0);
define ('CODIANA', 'codiana');


function codiana_string ($name, $args = null) {
//    return $name;
    if (func_num_args () == 1)
        return get_string ($name, CODIANA);
    $array = func_get_args ();
    array_shift ($array);
    return vsprintf (get_string ($name, CODIANA), $array);
}



class codiana_plag_state {

    /**
     * Waiting in queue for processing
     * @var int
     */

    const NOT_EXECUTED = 1;

    /**
     * Waiting in queue for processing
     * @var int
     */
    const WAITING_TO_PROCESS = 2;

    /**
     * Some duplicates were found
     * @var int
     */
    const PLAGS_FOUND = 3;

    /**
     * Some duplicates were found
     * @var int
     */
    const NO_PLAGS_FOUND = 4;

    /**
     * Processing has been aborted
     * @var int
     */
    const PROCESS_ABORTED = 99;

    //    const SOLUTION_MEAN = 3;

    /** @var array of all grade methods */
    public static $types = array (
        self::NOT_EXECUTED       => 'plagstate:check_not_yet_executed',
        self::WAITING_TO_PROCESS => 'plagstate:check_in_progress',
        self::PLAGS_FOUND        => 'plagstate:dupes_found',
        self::NO_PLAGS_FOUND     => 'plagstate:no_dupes_found',
        self::PROCESS_ABORTED    => 'plagstate:check_aborted'
    );



    /**
     * @param $value int state index
     * @return string state name
     */
    public static function get ($value) {
        return codiana_string (self::$types[$value]);
    }
}



class codiana_task_state {

    /** @var int task is active */
    const ACTIVE = 1;

    /** @var int task is not active */
    const NOT_ACTIVE = 2;

    /** @var array of all grade methods */
    public static $types = array (
        self::ACTIVE     => 'taskstate:active',
        self::NOT_ACTIVE => 'taskstate:not_active'
    );



    /**
     * @param $value int state index
     * @return string state name
     */
    public static function get ($value) {
        return codiana_string (self::$types[$value]);
    }
}



/**
 * Class codiana_state, helper class for task states
 */
class codiana_attempt_state {

    /** @var string */
    const PREFIX = 'state:';

    // ---------------------------------------------------------------------------------------------
    // ------- SPECIAL STATES ----------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------
    /**
     * Other non specific error
     * @var int
     */
    const OTHER_ERROR = 0;

    /**
     * Processing has been aborted
     * @var int
     */
    const PROCESS_ABORTED = 99;

    /**
     * Waiting in queue for processing
     * @var int
     */
    const WAITING_TO_PROCESS = 1;

    // ---------------------------------------------------------------------------------------------
    // ------- CODE SPECIFIC STATES ----------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------
    /**
     * Source code containg dangerous elements (packages, commands, etc.)
     * @var int
     */
    const CODE_DANGEROUS = 2;

    /**
     * Source code in either in non-readable state, or has broken rules for this task
     * @var int
     */
    const CODE_INVALID = 3;

    /**
     * Source code is valid and can be processed
     * @var int
     */
    const CODE_VALID = 4;

    // ---------------------------------------------------------------------------------------------
    // ------- COMPILATION STATES ------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------
    /**
     * Error during compilation
     * @var int
     */
    const COMPILATION_ERROR = 5;

    /**
     * Compilation was not completed in time, usually broken code
     * @var int
     */
    const COMPILATION_TIMEOUT = 6;

    /**
     * Compilation was successful
     * @var int
     */
    const COMPILATION_OK = 7;

    // ---------------------------------------------------------------------------------------------
    // ------- RUN STATES --------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------
    /**
     * Error during execution
     * @var int
     */
    const EXECUTION_ERROR = 8;

    /**
     * Execution was not completed in time and was be terminated
     * @var int
     */
    const EXECUTION_TIMEOUT = 9;

    /**
     * Execution was successful
     * @var int
     */
    const EXECUTION_OK = 10;

    // ---------------------------------------------------------------------------------------------
    // ------- MEASURE STATES ----------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------
    /**
     * Task required output to be correct and it was not correct
     * @var int
     */
    const OUTPUT_ERROR = 11;

    /**
     * Task required execution time to be lower than some threshold and it took longer to execute
     * @var int
     */
    const TIME_ERROR = 12;

    /**
     * Task required execution memory peak to be lower than some threshold and it memory peak was
     * overstepped
     * @var int
     */
    const MEMORY_ERROR = 13;

    /**
     * Measurement was successful
     * @var int
     */
    const MEASUREMENT_OK = 14;

    //
    // ---------------------------------------------------------------------------------------------
    //


    public static $state = array (
        self::OTHER_ERROR         => 'other_error',
        self::PROCESS_ABORTED     => 'process_aborted',
        self::WAITING_TO_PROCESS  => 'waiting_to_process',

        self::CODE_DANGEROUS      => 'code_dangerous',
        self::CODE_INVALID        => 'code_invalid',
        self::CODE_VALID          => 'code_valid',

        self::COMPILATION_ERROR   => 'comiplation_error',
        self::COMPILATION_TIMEOUT => 'comiplation_timeout',
        self::COMPILATION_OK      => 'comiplation_ok',

        self::EXECUTION_ERROR     => 'execution_error',
        self::EXECUTION_TIMEOUT   => 'execution_timeout',
        self::EXECUTION_OK        => 'execution_ok',

        self::OUTPUT_ERROR        => 'output_error',
        self::TIME_ERROR          => 'time_error',
        self::MEMORY_ERROR        => 'memory_error',
        self::MEASUREMENT_OK      => 'measurement_ok',
    );



    /**
     * @param $value int state index
     * @return string state name
     */
    public static function get ($value) {
        return get_string (
            self::PREFIX .
            (array_key_exists ($value, self::$state) ?
                self::$state[$value] :
                self::$state['?']
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

    /** @var array of all users fields to edit */
    public static $fields = array ('basestat', 'state', 'runstat', 'resultstat', 'code');

    /** @var array of all users fields and managers fields */
    public static $managerFields = array ('basestat', 'state', 'runstat', 'resultstat', 'code', 'plags');
}



/**
 * Class codiana_grade_method, helper class for grade method values.
 * Which solution choose as main for grading
 */
class codiana_grade_method {

    const SOLUTION_FIRST = 1;

    const SOLUTION_LAST = 2;

    const SOLUTION_BEST = 3;

//    const SOLUTION_MEAN = 3;

    /** @var array of all grade methods */
    public static $types = array (
        self::SOLUTION_FIRST => 'grademethod:first',
        self::SOLUTION_LAST  => 'grademethod:last',
        self::SOLUTION_BEST  => 'grademethod:best',
//        codiana_grade_method::SOLUTION_MEAN => 'grademethod:mean'
    );



    /**
     * @param $value int state index
     * @return string state name
     */
    public static function get ($value) {
        return codiana_string (self::$types[$value]);
    }
}



/**
 * Class codiana_output_method, helper class for output method values
 * How to grade output
 */
class codiana_output_method {

    const GRADE_STRICT = 1;

    const GRADE_TOLERANT = 2;

    const GRADE_VAGUE = 3;

    /** @var array of all output methods */
    public static $types = array (
        codiana_output_method::GRADE_STRICT   => 'outputmethod:strict',
        codiana_output_method::GRADE_TOLERANT => 'outputmethod:tolerant',
        codiana_output_method::GRADE_VAGUE    => 'outputmethod:vague'
    );



    /**
     * @param $value int state index
     * @return string state name
     */
    public static function get ($value) {
        return codiana_string (self::$types[$value]);
    }
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

function codiana_check_int_optional ($codiana, $prop) {
    $enabled = $prop . '_enabled';
    if (!@$codiana->$enabled)
        $codiana->$prop = null;
    return codiana_check_int ($codiana, $prop, false);
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
    $filename  = $mform->get_new_filename ($name);
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
    $result    = $DB->get_records ('codiana_language', null, '', 'name,extension');
    $languages = array ();
    foreach ($result as $language)
        $languages[$language->name] = $language->extension;

    return $languages;
}

/**
 * @param $task stdClass task object
 * @return array all registered languages for given task
 * in array key is language name, value is language extension
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
    $languages    = array ();
    foreach ($result as $name) {
        if (array_key_exists ($name, $allLanguages))
            $languages[$name] = sprintf ('%s (*.%s)', $name, $allLanguages[$name]);
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
function codiana_get_user_all_attempts ($task, $userID, $fields = array (), $order = 'timesent DESC') {
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
function codiana_get_user_grade_attempt ($task, $userID, $fields = array ()) {
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

    $measureOK = codiana_attempt_state::MEASUREMENT_OK;
    $result    = $DB->get_record_sql (
        "SELECT $fields
        FROM
              {codiana_attempt} attempt
        LEFT JOIN {user} user ON
              (user.id = attempt.userid)
        WHERE
              (userid = :userid AND taskid = :taskid AND ordinal != -1 AND state = $measureOK)
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
function codiana_get_task_grade_attempts ($task) {
    global $DB;
    $order =
        ($task->grademethod == codiana_grade_method::SOLUTION_LAST ? 'timesent desc' :
            ($task->grademethod == codiana_grade_method::SOLUTION_FIRST ? 'timesent asc' :
                ($task->grademethod == codiana_grade_method::SOLUTION_BEST ? 'resultfinal desc' : NULL)
            )
        );

    if (is_null ($order))
        throw new Exception ('wrong task setting');

    $measureOK = codiana_attempt_state::MEASUREMENT_OK;
    $result    = $DB->get_records_sql (
        "SELECT
              attempt.*,
              CONCAT_WS (' ', UPPER(user.lastname), user.firstname) AS username,
              MAX(plags.result) AS plagresult,
              COUNT(distinct plags.id) AS plagscheckresult, plagstimecheck
        FROM
              {codiana_attempt} attempt
        LEFT JOIN {user} user ON
              (attempt.userid = user.id)
        LEFT JOIN {codiana_plags} plags ON
              (plags.firstid = user.id OR plags.secondid = user.id) AND plags.taskid = :taskid2
        WHERE
            (attempt.taskid = :taskid AND attempt.ordinal != -1 AND attempt.state = $measureOK)

        GROUP BY user.id
        ORDER BY $order, resultfinal DESC, username ASC",
        array ('taskid' => $task->id, 'taskid2' => $task->id),
        IGNORE_MISSING
    );
    if (empty($result))
        return array ();
    return $result;
}

/**
 * @param $task stdClass task object
 * @return array of result for each student having one or more attempt one row
 * @throws Exception when task is wrongly configured
 */
function codiana_get_task_all_attempts ($task) {
    global $DB;

    $result = $DB->get_records_sql (
        "SELECT
              attempt.*,
              CONCAT_WS (' ', UPPER(user.lastname), user.firstname) AS username
        FROM
              {codiana_attempt} attempt
        LEFT JOIN {user} user ON
              (attempt.userid = user.id)
        WHERE
            (taskid = :taskid)

        ORDER BY resultfinal DESC, username ASC",
        array ('taskid' => $task->id),
        IGNORE_MISSING
    );
    if (empty($result))
        return array ();
    return $result;
}

function codiana_format_dates_message ($time, $current = null) {
    $result = codiana_format_dates ($time, $current);
    return codiana_string ('date:' . $result[0], $result[1]);
}

function codiana_date_detail ($message, $time, $current = null) {
    return sprintf ("%s, %s", $message, codiana_format_dates_message ($time, $current));
}

function codiana_format_dates ($time, $current = null) {
    $current = is_null ($current) ? time () : $current;
    $diff    = $current - $time;


    if ($diff < 0) {
        // future
        $diff = -$diff;
        if ($diff <= (2 * 60))
            return array ('in_few_minutes', '');

        if ($diff <= (60 * 60))
            return array ('in_x_minutes', intval ($diff / 60));

        if ($diff <= (2 * 60 * 60))
            return array ('in_one_hour', intval ($diff / (60 * 60)));

        if ($diff <= (24 * 60 * 60))
            return array ('in_x_hours', intval ($diff / (60 * 60)));

        if ($diff <= (48 * 60 * 60))
            return array ('tomorow', '');

        return array ('x_days_from_now', intval ($diff / (24 * 60 * 60)));

    } else {

        // past
        if ($diff <= (2 * 60))
            return array ('justnow', '');

        if ($diff <= (60 * 60))
            return array ('x_minutes_ago', intval ($diff / 60));

        if ($diff <= (2 * 60 * 60))
            return array ('hour_ago', intval ($diff / (60 * 60)));

        if ($diff <= (24 * 60 * 60))
            return array ('x_hours_ago', intval ($diff / (60 * 60)));

        if ($diff <= (48 * 60 * 60))
            return array ('yesterday', '');

        return array ('x_days_ago', intval ($diff / (24 * 60 * 60)));
    }
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
    $time  = $time == null ? time () : intval ($time);
    $open  = $task->timeopen;
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
 * @param $task stdClass task object
 * @param $time int default time value, if null, current time will be used
 * @return bool wheter is task has started (depends on time)
 */
function codiana_has_task_started ($task, $time = null) {
    $time = $time == null ? time () : intval ($time);
    $open = $task->timeopen;

    if (is_null ($open) || $time >= $open)
        return true;

    return false;
}

/**
 * @param $task stdClass task object
 * @param $time int default time value, if null, current time will be used
 * @return bool wheter is task has ended (depends on time)
 */
function codiana_has_task_ended ($task, $time = null) {
    $time  = $time == null ? time () : intval ($time);
    $close = $task->timeclose;

    if (is_null ($close) || $time >= $close)
        return true;

    return false;
}

function codiana_is_task_active ($task) {
    return $task->state == codiana_task_state::ACTIVE;
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
    $fields  = array ();
    $i       = $mask;
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
                $result['id']       = 'attempt.id';
                $result['username'] = "CONCAT_WS (' ', UPPER(user.lastname), user.firstname) AS username";
                $result['timesent'] = "attempt.timesent";
                $result['ordinal']  = "attempt.ordinal";
                break;
            case 'runstat':
                $result['runtime']   = 'attempt.runtime';
                $result['runmemory'] = 'attempt.runmemory';
                $result['runoutput'] = 'attempt.runoutput';
                break;
            case 'resultstat':
                $result['resulttime']   = 'attempt.resulttime';
                $result['resultmemory'] = 'attempt.resultmemory';
                $result['resultoutput'] = 'attempt.resultoutput';
                $result['resultfinal']  = 'attempt.resultfinal';
                break;

            case 'code':
                $result['taskid']     = 'attempt.taskid';
                $result['userid']     = 'attempt.userid';
                $result['resultnote'] = 'attempt.resultnote';
                break;

            case 'state':
                $result['state'] = 'state';
                break;
            case 'detail':
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

function codiana_get_user_attempt_count ($task, $userID) {
    global $DB;
    return $DB->count_records_sql (
        "SELECT COUNT(userid)
        FROM
              {codiana_attempt}
        WHERE (
              (userid != :userid AND taskid = :taskid AND ordinal != -1)
        )", array ('userid' => $userID, 'taskid' => $task->id),
        IGNORE_MISSING);
}

function codiana_get_user_count ($task, $userID) {
    // TODO x správných řešení? AND state = 14
    // ukázat kolik je uživatelů
    global $DB;
    $value = $DB->get_field_sql (
        "SELECT COUNT(DISTINCT userid) as totalusers
        FROM
              {codiana_attempt}
        WHERE
              (userid != :userid AND taskid = :taskid AND ordinal != -1)",
        array ('userid' => $userID, 'taskid' => $task->id),
        IGNORE_MISSING);
    return intval ($value);
}



class codiana_file_path_type {

    const TASK_INPUT = 1;

    const TASK_OUTPUT = 2;

    const TASK_DATA = 3;

    const TASK_FOLDER = 4;

    const USER_SOLUTION = 5;

    const USER_PREVIOUS_FOLDER = 6;

    const USER_CURRENT_FOLDER = 7;

    const USER_PREVIOUS_ZIP = 8;

    const FOLDER_DATA = 9;

    const ZIP_HIST_FILE = 10;

}



class codiana_output_type {

    const OUTPUT_FILE = 1;

    const SOLUTION = 2;
}



function codiana_get_file_path ($type) {
    switch ($type) {
        case codiana_file_path_type::FOLDER_DATA:
            return get_config ('codiana', 'storagepath');
    }
}


function codiana_get_task_file_path ($task, $type) {
    $dataDir      = get_config ('codiana', 'storagepath');
    $codianaID    = sprintf ('task-%04d', $task->id);
    $mainfilename = $task->mainfilename;
    switch ($type) {
        case codiana_file_path_type::TASK_INPUT:
            return $dataDir . "$codianaID/data/$mainfilename.in";
        case codiana_file_path_type::TASK_OUTPUT:
            return $dataDir . "$codianaID/data/$mainfilename.out";
        case codiana_file_path_type::TASK_DATA:
            return $dataDir . "$codianaID/data/";
        case codiana_file_path_type::TASK_FOLDER:
            return $dataDir . $codianaID . "/";
        case codiana_file_path_type::ZIP_HIST_FILE:
            return $dataDir . "/hist/$codianaID.zip";
    }
}

function codiana_get_user_file_path ($task, $type, $id, $detail = null) {
    $dataDir      = get_config ('codiana', 'storagepath');
    $codianaID    = sprintf ('task-%04d', $task->id);
    $userID       = sprintf ('user-%04d', $id);
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

/**
 * Method will return file status of given task
 * @param $task to be checked
 * @return array key input and ouput
 */
function codiana_get_files_status ($task) {
    $result           = array ();
    $transfer         = codiana_get_file_transfer ();
    $result['input']  = $transfer->exists (codiana_get_task_file_path ($task, codiana_file_path_type::TASK_INPUT));
    $result['output'] = $transfer->exists (codiana_get_task_file_path ($task, codiana_file_path_type::TASK_OUTPUT));
    return $result;
}


/**
 * Method will hand over solution
 * @param $task into which solution will be handed over
 * @param $userID id of a user handing over solution
 * @param $mform ref to mform which holds task ref
 * @param $data type|priority|attemp
 * @return bool T/F
 * @throws moodle_exception on error
 */
function codiana_save_user_solution ($task, $userID, $mform, $data) {
    global $DB;

    // process details
    $data = is_object ($data) ? $data : (object)$data;

    // mform constants
    $content   = $mform->get_file_content ($data->elementName);
    $language  = $mform->language;
    $isZipped  = $mform->isZipped;
    $extension = $mform->extension;

    // generate paths
    $currentFolder  = codiana_get_user_file_path ($task, codiana_file_path_type::USER_CURRENT_FOLDER, $userID);
    $previousFolder = codiana_get_user_file_path ($task, codiana_file_path_type::USER_PREVIOUS_FOLDER, $userID);
    $solutionFile   = codiana_get_user_file_path ($task, codiana_file_path_type::USER_SOLUTION, $userID, $extension);
    $zippedFile     = codiana_get_user_file_path ($task, codiana_file_path_type::USER_PREVIOUS_ZIP, $userID, $data->attempt);

    // grap codiana_get_file_transfer object
    $fileTransfer = codiana_get_file_transfer ();

    // delete old solution and recreate folder
    if (!$fileTransfer->deleteDir ($currentFolder))
        throw new moodle_exception ('error:cannotdeletefolder', 'codiana');
    if (!$fileTransfer->mkDir ($currentFolder) || !$fileTransfer->mkDir ($previousFolder))
        throw new moodle_exception ('error:cannotcreatefolder', 'codiana');

    // save new solution
    if (!$fileTransfer->saveFile ($content, $solutionFile))
        throw new moodle_exception ('error:cannotsavesolution', 'codiana');

    // if zipped solution, unzip to location and delete zip file
    if ($isZipped) {
        if (!$fileTransfer->unzip ($solutionFile, $currentFolder))
            throw new moodle_exception ('error:cannotunzipsolution', 'codiana');
        if (!$fileTransfer->deleteFile ($solutionFile))
            throw new moodle_exception ('error:cannotdeletesolution', 'codiana');
    }

    // zip solution
    if (!$fileTransfer->zipDir ($currentFolder, $zippedFile))
        throw new moodle_exception ('error:cannotzipsolution', 'codiana');

    // TODO use codiana_is_zip comparison, not == 'zip'
    $attempt = array (
        'taskid'   => $task->id,
        'userid'   => $userID,
        'timesent' => time (),
        'state'    => codiana_attempt_state::WAITING_TO_PROCESS,
        'language' => $language,
        'detail'   => $isZipped ? 1 : 0,
        'ordinal'  => $data->attempt
    );

    $attemptID = $DB->insert_record (
        'codiana_attempt',
        (object)$attempt,
        true
    );

    $queue = array (
        'taskid'    => $task->id,
        'userid'    => $userID,
        'attemptid' => $attemptID,
        'type'      => $data->type,
        'priority'  => $data->priority
    );

    $queueID = $DB->insert_record (
        'codiana_queue',
        (object)$queue,
        true
    );

    codiana_trigger_java_update ();

    return true;
}

/**
 * Method willl saves teachers proto solution for measurement
 * @param $task ref to desired task
 * @param $mform ref where file ref will is
 * @return array messages array
 */
function codiana_save_proto_solution ($task, $mform) {
    global $USER, $DB;

    $result        = array ();
    $totalAttempts = intval ($DB->get_field_sql (
                                 'SELECT MAX(ordinal)
                                         FROM {codiana_attempt}
                                     WHERE
                                             userid = :userid AND
                                             taskid = :taskid AND
                                             ordinal > 0',
                                 array ('userid' => $USER->id,
                                        'taskid' => $task->id)));


    $lastAttempt = codiana_get_last_attempt ($task, $USER->id);
    $warning     = $totalAttempts > 0 && $lastAttempt->state == codiana_attempt_state::WAITING_TO_PROCESS;


    if ($warning) {
        // set state to aborted (last attempt) and delete any users queue items
        $lastAttempt->state = codiana_attempt_state::PROCESS_ABORTED;
        $DB->update_record ('codiana_attempt', $lastAttempt);
        $DB->delete_records ('codiana_queue', array ('userid' => $USER->id));
    }


    // save solution
    $data = array (
        'priority'    => 100,
        'elementName' => 'outputorsolutionfile',
        'attempt'     => $totalAttempts + 1,
        'type'        => codiana_queue_type::PROTO_CHECK
    );


    if (codiana_save_user_solution ($task, $USER->id, $mform, $data))
        $result[] = codiana_message::create ('protoinsertedintoqueue', 'info');
    else
        $result[] = codiana_message::create ('errorinsertingintoqueue', 'error');

    return $result;
}

/**
 * Method will save input file from given mform
 * @param $task specified task
 * @param $mform ref to mform
 * @return array messages
 */
function codiana_save_input_file ($task, $mform) {
    $result = array ();

    // define paths
    $inputPath = codiana_get_task_file_path ($task, codiana_file_path_type::TASK_INPUT);
    $dataPath  = codiana_get_task_file_path ($task, codiana_file_path_type::TASK_DATA);

    // grab file transfer
    $files = codiana_get_file_transfer ();
    $files->mkDir ($dataPath);

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

/**
 * Method will save input file from given mform
 * @param $task specified task
 * @param $mform ref to mform
 * @return array messages
 */
function codiana_save_output_file ($task, $mform) {
    $result = array ();

    // define paths
    $files      = codiana_get_file_transfer ();
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

/**
 * Method will generate input file from js objects
 * @param $task specified task
 * @return codiana_message|null on success or error messages
 */
function codiana_generate_input ($task) {
    $tmpFile         = tmpfile ();
    $info            = stream_get_meta_data ($tmpFile);
    $tmpFileLocation = $info['uri'];

    $result = codiana_generator_parser::generate ($tmpFile);
    if ($result == true) {
        $files             = codiana_get_file_transfer ();
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
        $this->content    = $content;
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
        return new codiana_message(get_string ('message:' . $id, $component), $class);
    }
}



/**
 * Method sends socket message to java requesting update
 * @return bool|codiana_message message error or true on success
 */
function codiana_trigger_java_update () {
    $ip      = get_config ('codiana', 'javaip');
    $port    = get_config ('codiana', 'javaport');
    $message = get_config ('codiana', 'javamessage');

    $socket = socket_create (AF_INET, SOCK_STREAM, SOL_TCP);

    if (@socket_connect ($socket, $ip, $port) == false)
        return codiana_message::create ('cannotconnecttoserver', 'error');

    if (@socket_write ($socket, $message) == false)
        return codiana_message::create ('cannotsendmessagetoserver', 'error');

    return true;
}


function codiana_get_task_plags ($task) {
    global $DB;
    $result = $DB->get_records_sql (
        "SELECT
              plags.id,
              CONCAT_WS (' ', UPPER(userA.lastname), userA.firstname) AS first,
              CONCAT_WS (' ', UPPER(userB.lastname), userB.firstname) AS second,
              plags.*
        FROM
              {codiana_plags} plags
        LEFT JOIN {user} userA ON
              (plags.firstid = userA.id)
        LEFT JOIN {user} userB ON
              (plags.secondid = userB.id)
        WHERE
            (taskid = :taskid)

        ORDER BY result DESC",
        array ('taskid' => $task->id),
        IGNORE_MISSING
    );
    return $result;
}




function codiana_delete_previous_plags ($task, $userID) {
    global $DB;

    // if this is entire solution check for thr first time
    // we need to delete records of 'one solution plag check' in same task
    // otherwise we only delete queue items related to this user and task
    if ($userID == CODIANA_ALL_USERS_ID) {
        $DB->delete_records (
            'codiana_queue',
            array (
                  'taskid' => $task->id,
                  'type'   => codiana_queue_type::PLAGIARISM_CHECK
            ));
        $DB->delete_records (
            'codiana_plags',
            array (
                  'taskid' => $task->id
            ));
    } else {
        $DB->delete_records (
            'codiana_queue',
            array (
                  'taskid' => $task->id,
                  'userid' => $userID,
                  'type'   => codiana_queue_type::PLAGIARISM_CHECK
            ));
        $DB->delete_records (
            'codiana_plags',
            array (
                  'taskid'  => $task->id,
                  'firstid' => $userID
            ));
        $DB->delete_records (
            'codiana_plags',
            array (
                  'taskid'   => $task->id,
                  'secondid' => $userID
            ));
    }
}

/**
 * @param $task
 * @param $attemptID
 * @return mixed
 */
function codiana_get_attempt ($task, $attemptID) {
    global $DB;
    return $DB->get_record (
        'codiana_attempt',
        $plagCheck = array (
            'id'     => $attemptID,
            'taskid' => $task->id
        ), '*', IGNORE_MISSING);
}

/**
 * @param $attempt
 * @return bool
 */
function codiana_set_attempt ($attempt) {
    global $DB;
    return $DB->update_record ('codiana_attempt', $attempt);
}

/**
 * @param $task
 * @return bool
 */
function codiana_set_task ($task) {
    global $DB;
    return $DB->update_record ('codiana', $task);
}


function codiana_get_from_id ($id = null, $printError = true) {
    if (is_null ($id)) {
        $id = optional_param ('id', 0, PARAM_INT);
        if (!$id)
            $id = optional_param ('update', 0, PARAM_INT);
    }
    $id = intval ($id);

    global $DB;
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
        return array ($cm, $course, $codiana);
    } else {
        if ($printError)
            print_error ('invalidcoursemodule');
        else
            return array (null, null, null);
    }
}


function codiana_find_by_prop ($array, $props) {
    $res = null;
    foreach ($array as $item) {
        $hasAll = true;
        $res    = $item;
        foreach ($props as $prop => $value) {
            if ($item->$prop != $value) {
                $hasAll = false;
                break;
            }
        }
        if ($hasAll)
            return $res;
    }
    return null;
}

function codiana_keep_properties ($array, $names) {
    $result = array ();
    foreach ($names as $name)
        $result[$name] = $array[$name];
    return $result;
}

function codiana_remove_keys ($array, $keys) {
    foreach ($keys as $key) {
        if (($index = array_search ($key, $array)) !== false)
            unset ($array[$index]);
    }
    return $array;
}

function codiana_create_url ($base, $attrs) {
    return codiana_create_moodle_url ($base, $attrs)->out (false);
}

function codiana_create_moodle_url ($base, $attrs) {
    return new moodle_url("/mod/codiana/$base", $attrs);
}

function codiana_create_page_title ($task, $title = '') {
    if ($title == '')
        return codiana_string ('title:task_x', $task->name);
    return codiana_string ('title:task_x_title', $task->name, codiana_string ($title));
}


function codiana_get_stat ($task, $column, $resolution = 1) {
    global $DB;
    if (is_null ($resolution))
        return array ();

    $result = $DB->get_records_sql (
        "SELECT
                FLOOR($column/$resolution)*$resolution AS result,
                COUNT(id) AS total
        FROM    {codiana_attempt}
        WHERE (taskid = :taskid AND $column IS NOT NULL)
        GROUP BY
                result
        ORDER BY
                total",
        array ('taskid' => $task->id),
        IGNORE_MISSING
    );
    unset ($result[""]);
    return $result;
}


function codiana_get_stat_resolution ($task) {
    global $DB;

    return $DB->get_record_sql (
        "SELECT
                FLOOR((MAX(runtime)-MIN(runtime))/5) AS runtime,
                FLOOR((MAX(resultfinal)-MIN(resultfinal))/5) AS resultfinal,
                FLOOR((MAX(runmemory)-MIN(runmemory))/5) AS runmemory,
                1 AS state
        FROM    {codiana_attempt}
        WHERE (taskid = :taskid)",
        array ('taskid' => $task->id),
        IGNORE_MISSING
    );
}

/*
A = 2
B = 7
C = 6

D = 6
E = 9
F = 8

G = 0
H = 6
J = 9

K = 2
L = 1
M = 4

50.45 901
14.33.602

*/