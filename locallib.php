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

class codiana_state {

    private static $prefix = 'codiana:state:';

    private static $state = array (
        '0' => 'waitingtoprocess',
        '1' => 'correctsolution',
        '2' => 'wrongsolution',
        '3' => 'maxtimelimit',
        '4' => 'maxmemorylimit',
        '5' => 'compilationerror',
        '6' => 'runerror',
        '7' => 'looperror',
        '8' => 'dangerouscode',
        '9' => 'nomainclass',
        '?' => 'unkwnownerror'
    );



    /**
     * @param $value int state index
     * @return string state name
     */
    public static function get ($value) {
        return get_string (codiana_state::$prefix .
                           (array_key_exists ($value, codiana_state::$state) ?
                               codiana_state::$state[$value] :
                               codiana_state::$state['?']
                           ), 'codiana');
    }
}



class codiana_display_options {

    const OPEN_SOLVER = 0;

    const CLOSE_SOLVER = 1;

    const OPEN_OTHERS = 2;

    const CLOSE_OTHERS = 3;

    const COUNT = 4;

    public static $types = array (
        codiana_display_options::OPEN_SOLVER,
        codiana_display_options::CLOSE_SOLVER,
        codiana_display_options::OPEN_OTHERS,
        codiana_display_options::CLOSE_OTHERS);

    public static $fields = array ('username', 'state', 'runstat', 'resultstat', 'code');
}



/**
 * try to get $prop value from $codiana object and convert it to number
 * @param stdClass $codiana
 * @param string $prop
 * @param bool $required
 * @return bool success/failure
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
    require_once ($CFG->dirroot . '/mod/codiana/filelib.php');

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
    return new RemoteFileTransfer();
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

    return $result;
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
 * @return array of all supported languages in system
 * in array key is language extension, value is language name
 */
function codiana_get_all_attempts ($task, $userID) {
    global $DB;
    $result = $DB->get_records (
        'codiana_attempt',
        array (
              'taskid' => $task->id,
              'userid' => $userID
        ),
        'timesent DESC'
    );
    return $result;
}
