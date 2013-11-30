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
    include $CFG->dirroot . '/mod/codiana/filelib.php';

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
            $languages[$extension] = $allLanguages[$extension]->name;
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
 * @return bool|int false if no attemps int representing last task state
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
