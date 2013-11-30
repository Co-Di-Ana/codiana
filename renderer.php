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


class mod_codiana_renderer extends plugin_renderer_base {

    /** @var Codiana */
    private $codiana;

    /** @var mixed */
    private $context;

    /** @var mixed */
    private $cm;

    /** @var array */
    private $messages;

    /** @var mixed */
    private $course;



    public function init ($codiana, $cm, $context, $course, $messages = array ()) {
        $this->codiana = new Codiana($codiana);
        $this->cm = $cm;
        $this->context = $context;
        $this->messages = $messages;
        $this->course = $course;
    }



    /**
     * Generates page for guests
     */
    public function view_page_guest () {

        $output = '';
        $output .= $this->view_information ();
        return $output;
    }



    public function view_page_viewmyattempts ($gradeAttempt, $attempts) {

        $output = '';

        // no grade attempt means no attempts either
        if ($gradeAttempt == null) {
            $output .= html_writer::tag ('h1', 'No attempts yet');
            $output .= html_writer::start_span ();
            $output .= 'You can submit you solution ';
            $output .= html_writer::link (
                new moodle_url (
                    '/mod/codiana/submitsolution.php', array ('id' => $this->cm->id)),
                'here');
            $output .= html_writer::end_span ();
            return $output;

        } else {
            $output .= html_writer::tag ('h1', sprintf ("Results for task '%s'", $this->codiana->name));
            $output .= $this->view_attempts_table (array ($gradeAttempt));
        }


        // remove from all attempts
        if (array_key_exists ($gradeAttempt->id, $attempts))
            unset ($attempts[$gradeAttempt->id]);



        if (sizeof ($attempts) > 0) {
            $output .= html_writer::tag ('h1', sprintf ("Other results for task '%s'", $this->codiana->name));
            $output .= $this->view_attempts_table ($attempts);
        }

        return $output;
    }



    private function view_attempts_table ($attempts) {
        $output = '';

        $table = new html_table();
        $table->attributes['class'] = 'generaltable codianaattemptsummary';
        $table->attributes['style'] = 'width: 100%';
        $table->head = array ();
        $table->align = array ();
        $table->size = array ();


        $ignores = array ('id', 'taskid', 'userid');
        $keys = array_keys ((array)$attempts[key ($attempts)]);

        if (in_array('ordinal', $keys)) {
            unset($keys[array_search('ordinal', $keys)]);
            $keys = array_merge(array ('ordinal'), $keys);
        }

        foreach ($keys as $key => $value) {
            if (in_array ($value, $ignores)) {
                unset ($keys[$key]);
                continue;
            }
            $table->align[] = 'center';
            $table->size[] = '';
            $table->head[] = $value;
        }

        $i = count($attempts);
        $data = array ();
        foreach ($attempts as $attempt) {
            $row = array ();
            foreach ($keys as $key)
                $row[] = $this->view_cell_value ($key, $attempt->$key, $attempt);
            $data[] = $row;
        }
        $table->data = $data;
        $output .= html_writer::table ($table);

        return $output;
    }



    private function view_cell_value ($name, $value, $attempt=null) {
        switch ($name) {

            case 'detail':
                return $value == 0 ? 'simple' : 'complex';

            case 'timesent':
                return date ('H:i:s  (m.d.Y)', $value);

            case 'state':
                return codiana_state::get ($value);
            case 'code':
                return html_writer::link(
                    new moodle_url(
                        '/mod/codiana/sourcecode.php',
                        array (
                              'id' => $this->cm->id,
                              'userid' => $attempt->userid,
                              'ordinal' => $attempt->ordinal
                        )
                    ), 'download');

            default:
                return is_null ($value) ? '-' : $value;
        }
    }



    /**
     * Output the page information
     */
    private function view_information () {
        global $CFG;
        $output = '';
        $output .= $this->view_head ();
        $output .= $this->view_description ();


        $output .= $this->view_block (
            $this->view_code (
                get_string ('codiana:inputexample', 'codiana'),
                $this->codiana->inputexample));
        $output .= $this->view_block (
            $this->view_code (
                get_string ('codiana:outputexample', 'codiana'),
                $this->codiana->outputexample));

        $output .= $this->view_mainfile_warning ();
        return $output;
    }



    /**
     * @param $content
     * @param string $class
     * @return string
     */
    private function view_block ($content, $class = '') {
        $output = '';
        $output .= html_writer::start_tag ("div", array ('class' => $class));
        $output .= $content;
        $output .= html_writer::end_tag ("div");
        return $output;
    }



    /**
     * @param $label
     * @param $code
     * @return string
     */
    private function view_code ($label, $code) {
        $code = trim ($code);
        if (empty ($code))
            return "";
        $lines = preg_split ("/(\r\n|\n|\r)/", $code);
        if (!empty ($lines[sizeof ($lines) - 1])) $lines[] = "&nbsp;";

        $output = '';
        $output .= html_writer::tag ('h3', $label);
        $output .= html_writer::start_tag ("div", array ('class' => 'codiana_code_wrapper generalbox'));
        $output .= html_writer::start_tag ("div", array ('class' => 'codiana_code_example'));
        $output .= html_writer::start_tag ("ol");
        foreach ($lines as $line)
            $output .= html_writer::tag ('li', $line);
        $output .= html_writer::end_tag ("ol");
        $output .= html_writer::end_tag ("div");
        $output .= html_writer::end_tag ("div");
        return $output;
    }



    /**
     * @return string
     */
    private function view_head () {
        return $this->heading (format_string ($this->codiana->name), 2);
    }



    /**
     * @return string
     */
    private function view_mainfile_warning () {
        $output = '';
        $output .= sprintf (
            get_string ("codiana:view:mainfilename:warning", "codiana"),
            html_writer::tag ("span", $this->codiana->mainfilename, array ('class' => 'codiana_mainfilename_warning')),
            $this->codiana->mainfilename, $this->codiana->mainfilename
        );
        return $this->view_block ($output, 'generalbox');
    }



    /**
     * @return string
     */
    private function view_description () {
        $output = '';
        if ($this->codiana->intro)
            $output .= $this->box (format_module_intro ('codiana', $this->codiana, $this->cm->id), 'generalbox mod_introbox', 'codiana_intro');
        return $output;
    }
}



class Codiana extends stdClass {

    /** @var int */
    public $id;

    /** @var int */
    public $course;

    /** @var string */
    public $name;

    /** @var string */
    public $mainfilename;

    /** @var int */
    public $difficulty;

    /** @var int */
    public $grademethod;

    /** @var array */
    public $languages;

    /** @var string */
    public $intro;

    /** @var int */
    public $introformat;

    /** @var int */
    public $timecreated;

    /** @var int */
    public $timemodified;

    /** @var int */
    public $timeopen;

    /** @var int */
    public $timeclose;

    /** @var int */
    public $maxusers;

    /** @var int */
    public $maxattempts;

    /** @var int */
    public $limittime;

    /** @var int */
    public $limitmemory;

    /** @var string */
    public $inputexample;

    /** @var string */
    public $outputexample;

    /** @var array */
    private $props = array ('id', 'course', 'name', 'mainfilename', 'difficulty', 'grademethod',
                            'languages', 'intro', 'introformat', 'timecreated', 'timemodified',
                            'timeopen', 'timeclose', 'maxusers', 'maxattempts',
                            'limittime', 'limitmemory', 'inputexample', 'outputexample');



    public function __construct (stdClass $codiana) {
        foreach ($this->props as $prop)
            $this->$prop = empty($codiana->$prop) ? null : $codiana->$prop;
    }

}