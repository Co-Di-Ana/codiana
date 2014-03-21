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
 * Administration settings definitions for the quiz module.
 *
 * @package    mod
 * @subpackage quiz
 * @copyright  2010 Petr Skoda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined ('MOODLE_INTERNAL') || die();

require_once ($CFG->dirroot . '/mod/codiana/lib.php');
require_once ($CFG->dirroot . '/mod/codiana/settingslib.php');
require_once ($CFG->dirroot . '/mod/codiana/locallib.php');

$settings->add (
    new admin_setting_configtext(
        'codiana/limittime',
        get_string ('setting:limittime', 'codiana'),
        get_string ('setting:limittime_desc', 'codiana'),
        60, PARAM_INT, 4
    )
);


$settings->add (
    new admin_setting_configtext(
        'codiana/limitmemory',
        get_string ('setting:limitmemory', 'codiana'),
        get_string ('setting:limitmemory_desc', 'codiana'),
        500000, PARAM_INT, 4
    )
);




$settings->add (
    new admin_setting_heading(
        'name',
        get_string ('setting:storage', 'codiana'),
        get_string ('setting:storage_desc', 'codiana')
    )
);

$settings->add (
    new admin_setting_configcheckbox(
        'codiana/islocal',
        get_string ('setting:islocal', 'codiana'),
        get_string ('setting:islocal_desc', 'codiana'),
        1
    )
);

// TODO normalise path
$settings->add (
    new admin_setting_configtext(
        'codiana/storagepath',
        get_string ('setting:storagepath', 'codiana'),
        get_string ('setting:storagepath_desc', 'codiana'),
        '/home/codiana/data/', PARAM_TEXT, 100
    )
);


$settings->add (
    new admin_setting_configtext(
        'codiana/sshusername',
        get_string ('setting:sshusername', 'codiana'),
        get_string ('setting:sshusername_desc', 'codiana'),
        '', PARAM_ALPHANUMEXT, 30
    )
);


$settings->add (
    new admin_setting_configpasswordunmask (
        'codiana/sshpassword',
        get_string ('setting:sshpassword', 'codiana'),
        get_string ('setting:sshpassword_desc', 'codiana'),
        ''
    )
);

// -----------------------------------------------------------------------------
// ----- JAVA LOCATION ---------------------------------------------------------
// -----------------------------------------------------------------------------
$settings->add (
    new admin_setting_configtext(
        'codiana/javaip',
        get_string ('setting:javaip', 'codiana'),
        get_string ('setting:javaip_desc', 'codiana'),
        '127.0.0.1', PARAM_HOST, 30
    )
);
$settings->add (
    new admin_setting_configtext(
        'codiana/javaport',
        get_string ('setting:javaport', 'codiana'),
        get_string ('setting:javaport_desc', 'codiana'),
        '14700', PARAM_INT, 30
    )
);
$settings->add (
    new admin_setting_configtext(
        'codiana/javamessage',
        get_string ('setting:javamessage', 'codiana'),
        get_string ('setting:javamessage_desc', 'codiana'),
        'codiana.check', PARAM_TEXT, 30
    )
);


// -----------------------------------------------------------------------------
// ----- TASK DEFAULT SETTINGS -------------------------------------------------
// -----------------------------------------------------------------------------
$group = new admin_setting_configmulticheckbox_base_group(
    'codiana/setting',
    'Setting',
    'settings',
    '240383',
    codiana_display_options::$fields
);

$group->add (
    codiana_string ('settings:open_solver'),
    codiana_string ('settings:open_solver_desc'),
    codiana_display_options::OPEN_SOLVER
);
$group->add (
    codiana_string ('settings:close_solver'),
    codiana_string ('settings:close_solver_desc'),
    codiana_display_options::CLOSE_SOLVER
);
$group->add (
    codiana_string ('settings:active_others'),
    codiana_string ('settings:active_others_desc'),
    codiana_display_options::OPEN_OTHERS
);
$group->add (
    codiana_string ('settings:close_others'),
    codiana_string ('settings:close_others_desc'),
    codiana_display_options::CLOSE_OTHERS
);

$settings->add ($group);
