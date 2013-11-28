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
        get_string ('codiana:setting:limittime', 'codiana'),
        get_string ('codiana:setting:limittime_desc', 'codiana'),
        60, PARAM_INT, 4
    )
);


$settings->add (
    new admin_setting_configtext(
        'codiana/limitmemory',
        get_string ('codiana:setting:limitmemory', 'codiana'),
        get_string ('codiana:setting:limitmemory_desc', 'codiana'),
        100, PARAM_INT, 4
    )
);




$settings->add (
    new admin_setting_heading(
        'name',
        get_string ('codiana:setting:storage', 'codiana'),
        get_string ('codiana:setting:storage_desc', 'codiana')
    )
);

$settings->add (
    new admin_setting_configcheckbox(
        'codiana/islocal',
        get_string ('codiana:setting:islocal', 'codiana'),
        get_string ('codiana:setting:islocal_desc', 'codiana'),
        1
    )
);

// TODO normalise path
$settings->add (
    new admin_setting_configtext(
        'codiana/storagepath',
        get_string ('codiana:setting:storagepath', 'codiana'),
        get_string ('codiana:setting:storagepath_desc', 'codiana'),
        '/var/codiana/data', PARAM_TEXT, 100
    )
);


$settings->add (
    new admin_setting_configtext(
        'codiana/sshusername',
        get_string ('codiana:setting:sshusername', 'codiana'),
        get_string ('codiana:setting:sshusername_desc', 'codiana'),
        '', PARAM_ALPHANUMEXT, 30
    )
);


$settings->add (
    new admin_setting_configpasswordunmask (
        'codiana/sshpassword',
        get_string ('codiana:setting:sshpassword', 'codiana'),
        get_string ('codiana:setting:sshpassword_desc', 'codiana'),
        ''
    )
);

$group = new admin_setting_configmulticheckbox_base_group(
    'codiana/setting',
    'Setting',
    'settings',
    '174847',
    codiana_display_options::$fields
);

$group->add (
    'Active task and solver',
    'popis',
    codiana_display_options::OPEN_SOLVER
);
$group->add (
    'Task over and solver',
    'popis',
    codiana_display_options::CLOSE_SOLVER
);
$group->add (
    'Active task and others',
    'popis',
    codiana_display_options::OPEN_OTHERS
);
$group->add (
    'Task over and others',
    'popis',
    codiana_display_options::CLOSE_OTHERS
);

$settings->add ($group);