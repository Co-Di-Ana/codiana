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
 * Library of interface functions and constants for module codiana
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the codiana specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_codiana
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined ('MOODLE_INTERNAL') || die ();

/** example constant */
//define('codiana_ULTIMATE_ANSWER', array(1, 2, 3, 5));
////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////


/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function codiana_supports ($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;

        default:
            return null;
    }
}


/**
 * Saves a new instance of the codiana into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object|\stdClass $codiana An object from the form in mod_form.php
 * @param mod_codiana_mod_form $mform
 * @return mixed the id of the new instance on success,
 *          false or a string error message on failure.
 */
function codiana_add_instance (stdClass $codiana, mod_codiana_mod_form $mform = null) {
    global $DB;

    // data check
    $result = codiana_preprocess ($codiana);
    if (!$result) return $result;
    $codiana->timecreated  = time ();
    $codiana->timemodified = time ();



    return $DB->insert_record ('codiana', $codiana);
}


/**
 * Updates an instance of the codiana in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object|\stdClass $codiana An object from the form in mod_form.php
 * @param mod_codiana_mod_form $mform
 * @return boolean Success/Fail
 */
function codiana_update_instance (stdClass $codiana, mod_codiana_mod_form $mform = null) {
    global $DB;

    // data check
    $result = codiana_preprocess ($codiana);
    if (!$result) return $result;

    $codiana->timemodified = time ();
    $codiana->id           = $codiana->instance;

    return $DB->update_record ('codiana', $codiana);
}

/**
 *  Preprocesses given $codiana object
 * @param $codiana stdClass
 * @return string|boolean return true on success or string error message
 */
function codiana_preprocess (stdClass $codiana) {
    global $CFG;
    require_once ($CFG->dirroot . '/mod/codiana/locallib.php');

    $codiana->name         = codiana_parse_string (@$codiana->name);
    $codiana->mainfilename = codiana_parse_string (@$codiana->mainfilename);
    $codiana->grademethod  = codiana_parse_int (@$codiana->grademethod, 0);
    $codiana->outputmethod = codiana_parse_int (@$codiana->outputmethod, 0);
    $codiana->languages    = implode (',', @$codiana->languages);
    $codiana->difficulty   = codiana_parse_int (@$codiana->difficulty, 0);
    $codiana->intro        = codiana_parse_string (@$codiana->intro, "");
    $codiana->introformat  = codiana_parse_int (@$codiana->introformat, 0);

    $codiana->timeopen  = codiana_parse_int (@$codiana->timeopen, null);
    $codiana->timeclose = codiana_parse_int (@$codiana->timeclose, null);

    $codiana->maxusers    = codiana_parse_int (@$codiana->maxusers);
    $codiana->maxattempts = codiana_parse_int (@$codiana->maxattempts);
    $codiana->limittime   = codiana_parse_int (@$codiana->limittime);
    $codiana->limitmemory = codiana_parse_int (@$codiana->limitmemory);

    $codiana->solutionfile = @$codiana->solutionfile;
    $codiana->inputfile    = @$codiana->inputfile;
    $codiana->outputfile   = @$codiana->outputfile;
    $codiana->errorfile    = @$codiana->errorfile;

    $codiana->inputexample  = codiana_parse_string (@$codiana->inputexample);
    $codiana->outputexample = codiana_parse_string (@$codiana->outputexample);

    $codiana->visible    = @$codiana->visible;
    $codiana->cmidnumber = @$codiana->cmidnumber;
    $codiana->groupmode  = @$codiana->groupmode;

    $codiana->settings = array_sum (@$codiana->setting);

    return true;
}


/**
 * Removes an instance of the codiana from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function codiana_delete_instance ($id) {
    global $DB;

    if (!$codiana = $DB->get_record ('codiana', array ('id' => $id))) {
        return false;
    }

    # Delete any dependent records here #

    $DB->delete_records ('codiana', array ('id' => $codiana->id));

    return true;
}


/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function codiana_user_outline ($course, $user, $mod, $codiana) {

    $return       = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}


/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $codiana the module instance record
 * @return void, is supposed to echp directly
 */
function codiana_user_complete ($course, $user, $mod, $codiana) {

}


/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in codiana activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function codiana_print_recent_activity ($course, $viewfullnames, $timestart) {
    return false; //  True if anything was printed, otherwise false
}


/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link codiana_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function codiana_get_recent_mod_activity (&$activities, &$index, $timestart, $courseid, $cmid, $userid = 0, $groupid = 0) {

}


/**
 * Prints single activity item prepared by {@see codiana_get_recent_mod_activity()}
 * @return void
 */
function codiana_print_recent_mod_activity ($activity, $courseid, $detail, $modnames, $viewfullnames) {

}


/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 * */
function codiana_cron () {
    return true;
}


/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function codiana_get_extra_capabilities () {
    return array ();
}


////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////


/**
 * Is a given scale used by the instance of codiana?
 *
 * This function returns if a scale is being used by one codiana
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $codianaid ID of an instance of this module
 * @return bool true if the scale is used by the given codiana instance
 */
function codiana_scale_used ($codianaid, $scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists ('codiana', array ('id' => $codianaid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}


/**
 * Checks if scale is being used by any instance of codiana.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any codiana instance
 */
function codiana_scale_used_anywhere ($scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists ('codiana', array ('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}


/**
 * Creates or updates grade item for the give codiana instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $codiana instance object with extra cmidnumber and modname property
 * @param mixed optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return void
 */
function codiana_grade_item_update (stdClass $codiana, $grades = null) {
    global $CFG;
    require_once ($CFG->libdir . '/gradelib.php');

    /** @example */
    $item              = array ();
    $item['itemname']  = clean_param ($codiana->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax']  = $codiana->grade;
    $item['grademin']  = 0;

    grade_update ('mod/codiana', $codiana->course, 'mod', 'codiana', $codiana->id, 0, null, $item);
}


/**
 * Update codiana grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $codiana instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function codiana_update_grades (stdClass $codiana, $userid = 0) {
    global $CFG, $DB;
    require_once ($CFG->libdir . '/gradelib.php');

    /** @example */
    $grades = array (); // populate array of grade objects indexed by userid

    grade_update ('mod/codiana', $codiana->course, 'mod', 'codiana', $codiana->id, 0, $grades);
}


////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////


/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function codiana_get_file_areas ($course, $cm, $context) {
    return array ();
}


/**
 * File browsing support for codiana file areas
 *
 * @package mod_codiana
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function codiana_get_file_info ($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}


/**
 * Serves the files from the codiana file areas
 *
 * @package mod_codiana
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the codiana's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function codiana_pluginfile ($course, $cm, $context, $filearea, array $args, $forcedownload, array $options = array ()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found ();
    }

    require_login ($course, true, $cm);

    send_file_not_found ();
}


////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////


/**
 * Extends the global navigation tree by adding codiana nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the codiana module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function codiana_extend_navigation (navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {

}

/**
 * Extends the settings navigation with the codiana settings
 *
 * This function is called when the context for the page is a codiana module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $codiananode {@link navigation_node}
 */
function codiana_extend_settings_navigation (settings_navigation $settingsnav, navigation_node $codiananode = null) {
    global $PAGE, $CFG;

    $keys      = $codiananode->get_children_key_list ();
    $beforeKey = sizeof ($keys) > 0 ? $keys[0] : null;
    $context   = $PAGE->cm->context;
    $id        = $PAGE->cm->id;

    // can submit solution
    if (has_capability ('mod/codiana:submitsolution', $context)) {
        $url  = new moodle_url('/mod/codiana/submitsolution.php', array ('id' => $id, 'sesskey' => sesskey ()));
        $node = navigation_node::create ("Odevzdat řešení", $url, navigation_node::TYPE_SETTING, null, 'mod_codiana_submitsolution');
        $codiananode->add_node ($node, $beforeKey);
    }

    // can view ones results
    if (has_capability ('mod/codiana:viewmyattempts', $context)) {
        $url  = new moodle_url('/mod/codiana/viewmyattempts.php', array ('id' => $id, 'sesskey' => sesskey ()));
        $node = navigation_node::create ("Zobrazit výsledky", $url, navigation_node::TYPE_SETTING, null, 'mod_codiana_viewmyattempts');
        $codiananode->add_node ($node, $beforeKey);
    }

    // can manage task files
    if (has_capability ('mod/codiana:managetaskfiles', $context)) {
        $url  = new moodle_url('/mod/codiana/managefiles.php', array ('id' => $id, 'sesskey' => sesskey ()));
        $node = navigation_node::create ("Manage files", $url, navigation_node::TYPE_SETTING, null, 'mod_codiana_managetaskfiles');
        $codiananode->add_node ($node, $beforeKey);

        $url  = new moodle_url('/mod/codiana/generateinput.php', array ('id' => $id, 'sesskey' => sesskey ()));
        $node = navigation_node::create ("Generate input file", $url, navigation_node::TYPE_SETTING, null, 'mod_codiana_generateinput');
        $codiananode->add_node ($node, $beforeKey);
    }

    // can view all results
    if (has_capability ('mod/codiana:viewresults', $context)) {
        $url  = new moodle_url('/mod/codiana/viewresults.php', array ('id' => $id, 'sesskey' => sesskey ()));
        $node = navigation_node::create ("View results", $url, navigation_node::TYPE_SETTING, null, 'mod_codiana_viewresults');
        $codiananode->add_node ($node, $beforeKey);
    }

//    $url = new moodle_url('/mod/codiana/submitsolution.php');
//    $node = navigation_node::create (get_string ('view', 'codiana'), $url,
//        navigation_node::TYPE_ROOTNODE, null, 'mod_codiana_view');
//    $codiananode->add_node ($node);
//    $codiananode->add_node (navigation_node::create ("ahoj"));
//
//    $keys = $codiananode->get_children_key_list ();
//    print_r ($keys);
}



