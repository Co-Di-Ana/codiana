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

$settings->add (
    new admin_setting_configtext(
        'codiana/maxexectime',
        'Maximum execution time',
        'Maximum allowed execution time in seconds',
        60, PARAM_INT, 4
    )
);

$settings->add (new admin_setting_heading('name', 'heading', null));
//
//$settings->add (new admin_setting_configmulticheckbox(
//                    'condiana_languages3',
//                    'Languages',
//                    'Installed languages',
//                    array (1, 2),
//                    array ('none', 'java', 'c', 'c++', 'python')
//                )
//);
//
//$settings->add (new admin_setting_configmulticheckbox2(
//                    'condiana/languages3',
//                    'Languages',
//                    'Installed languages',
//                    array (1, 2),
//                    array ('none', 'java', 'c', 'c++', 'python')
//                )
//);
//
//$settings->add (new admin_setting_configtext_with_advanced(
//                    'codiana/languages',
//                    'Languages',
//                    'Installed languages',
//                    array ('value' => '0', 'adv' => false),
//                    PARAM_TEXT)
//);
//
//
//$settings->add (new admin_setting_configselect_with_advanced(
//                    'codiana/languages2',
//                    'Languages',
//                    'Installed languages',
//                    array ('value' => -1, 'adv' => true),
//                    array (-1 => 'none', 'java', 'c', 'c++', 'python')
//                )
//);