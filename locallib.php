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
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 */
//function codiana_do_something_useful(array $things) {
//    return new stdClass();
//}


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

function codiana_parse_string ($value, $default = null) {
    $value = empty($value) ? $default : trim ($value);
    return $value;
}

function codiana_parse_int ($value, $default = null) {
    $value = empty($value) ? $default : intval ($value);
    return $value;
}


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


function codiana_solution_get_extension($mform, $name) {
    $filename = $mform->get_new_filename ($name);
    $extension = pathinfo ($filename, PATHINFO_EXTENSION);

    return $extension;
}