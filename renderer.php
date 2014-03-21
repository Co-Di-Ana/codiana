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
 * Defines the renderer for the quiz module.
 *
 * @package    mod
 * @subpackage quiz
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined ('MOODLE_INTERNAL') || die();

require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
require_once ($CFG->dirroot . '/mod/codiana/renderers.php');


class mod_codiana_renderer extends plugin_renderer_base {

    /** @var object */
    public $codiana;

    /** @var mixed */
    public $context;

    /** @var mixed */
    public $cm;

    /** @var array */
    public $messages;

    /** @var mixed */
    public $course;

    /** @var int */
    private $currentTime;


    private $classMain = array ('class' => 'main');



    public function init ($codiana, $cm, $context, $course, $messages = array ()) {
        $this->codiana  = $codiana;
        $this->cm       = $cm;
        $this->context  = $context;
        $this->messages = $messages;
        $this->course   = $course;
    }



    /**
     * Generates page for guests
     */
    public function view_page_guest () {

        $output = '';
        $output .= $this->view_information ();
        return $output;
    }



    public function codiana_attempts_table ($gradeAttempt, $attempts) {
        $renderer = new codiana_task_detail($this);
        return $renderer->view_page ($gradeAttempt, $attempts);
    }



    public function view_page_viewresults ($attempts, $showAll, $plags) {
        $renderer = new codiana_allattempts_table($this);
        return $renderer->view_page2 ($attempts, $showAll, $plags);
    }



    public function view_page_not_active ($grade = null) {
        $renderer = new codiana_task_not_active_detail($this);
        return $renderer->view_page ($grade);
    }



    public function view_task_details ($isManager = false, $grade = null, $attempt = null) {
        $renderer = new codiana_task_detail($this);
        return $renderer->view_page ($isManager, $grade, $attempt);
    }



    public function view_page_show_grades ($grades, $users, $attempts) {
        $renderer = new codiana_task_grade_table($this);
        return $renderer->view_page ($grades, $users, $attempts);
    }



    public function view_page_show_user_grade ($grade) {
        $renderer = new codiana_user_grade_table($this);
        return $renderer->view_page ($grade);
    }



    public function view_edit_result ($attempt) {
        $renderer = new codiana_attempt_edit_table($this);
        return $renderer->view_page ($attempt);
    }



    public function view_page_viewmyattempts ($gradeAttempt, $allAttempts) {
        $renderer = new codiana_myattempts_table($this);
        return $renderer->view_page ($gradeAttempt, $allAttempts);
    }



    public function view_task_stats ($stats) {
        $renderer = new codiana_stats_renderer($this);
        return $renderer->view_page ($stats);
    }

}

