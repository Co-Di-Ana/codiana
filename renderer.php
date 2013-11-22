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



    /**
     * Generates page for guests
     *
     * @param int $course The course ID
     * @param stdClass $codiana Array contingin quiz data
     * @param int $cm Course Module ID
     * @param int $context The page contect ID
     * @param array $messages Array containing any messages
     * @return string HTML to output.
     */
    public function view_page_guest ($course, $codiana, $cm, $context, $messages) {
        global $CFG;
        $this->codiana = new Codiana($codiana);
        $this->cm = $cm;
        $this->context = $context;

        $output = '';
        $output .= $this->view_information ($this->codiana, $cm, $context, $messages);

        //        $table = new html_table();
//        $table->attributes['class'] = 'generaltable codianaattemptsummary';
//        $table->head = array ();
//        $table->align = array ();
//        $table->size = array ();
//
//        $table->align[] = 'center';
//        $table->size[] = '';
//        $table->head[] = get_string ('grade');
//        $table->align[] = 'center';
//        $table->size[] = '';
//        $table->head[] = get_string ('review', 'quiz');
//        $table->align[] = 'center';
//        $table->size[] = '';
//        $table->head[] = get_string ('feedback', 'quiz');
//        $table->align[] = 'left';
//        $table->size[] = '';
//        $table->data = array (array ('cascas', 'cascas', 'ndfgngf', 'bdf'), array ('cascas', 'cascas', 'ndfgngf', 'bdf'));
//        $output .= html_writer::table ($table);
        return $output;
    }



    /**
     * Output the page information
     *
     * @param Codiana $codiana the quiz settings.
     * @param object $cm the course_module object.
     * @param object $context the quiz context.
     * @param array $messages any access messages that should be described.
     * @return string HTML to output.
     */
    private function view_information (Codiana $codiana, $cm, $context, $messages) {
        global $CFG;
        $output = '';
        $output .= $this->view_head ();
        $output .= $this->view_description ();


        $output .= $this->view_block (
            $this->view_code (
                get_string ('codiana:taskinputexample', 'codiana'),
                $this->codiana->taskinputexample));
        $output .= $this->view_block (
            $this->view_code (
                get_string ('codiana:taskoutputexample', 'codiana'),
                $this->codiana->taskoutputexample));

        $output .= $this->view_taskmainfile_warning ();
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
    private function view_taskmainfile_warning () {
        $output = '';
        $output .= sprintf (
            get_string ("codiana:view:taskmainfilename:warning3", "codiana"),
            html_writer::tag ("span", $this->codiana->taskmainfilename, array ('class' => 'codiana_taskmainfilename_warning')),
            $this->codiana->taskmainfilename, $this->codiana->taskmainfilename
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
    public $taskmainfilename;

    /** @var int */
    public $taskdifficulty;

    /** @var int */
    public $taskgrademethod;

    /** @var array */
    public $tasklanguages;

    /** @var string */
    public $intro;

    /** @var int */
    public $introformat;

    /** @var int */
    public $timecreated;

    /** @var int */
    public $timemodified;

    /** @var int */
    public $tasktimeopen;

    /** @var int */
    public $tasktimeclose;

    /** @var int */
    public $taskmaxusers;

    /** @var int */
    public $taskmaxattempts;

    /** @var int */
    public $tasklimittime;

    /** @var int */
    public $tasklimitmemory;

    /** @var string */
    public $taskinputexample;

    /** @var string */
    public $taskoutputexample;

    /** @var array */
    private $props = array ('id', 'course', 'name', 'taskmainfilename', 'taskdifficulty', 'taskgrademethod',
                            'tasklanguages', 'intro', 'introformat', 'timecreated', 'timemodified',
                            'tasktimeopen', 'tasktimeclose', 'taskmaxusers', 'taskmaxattempts',
                            'tasklimittime', 'tasklimitmemory', 'taskinputexample', 'taskoutputexample');



    public function __construct (stdClass $codiana) {
        foreach ($this->props as $prop)
            $this->$prop = empty($codiana->$prop) ? null : $codiana->$prop;
    }

}