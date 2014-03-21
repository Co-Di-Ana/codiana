<?php
/**
 * Created by PhpStorm.
 * User: Jan
 * Date: 3.3.14
 * Time: 20:59
 */


abstract class codiana_renderer_base {

    protected $classMain = array ('class' => 'main');

    /** @var object */
    protected $codiana;

    /** @var mixed */
    protected $context;

    /** @var mixed */
    protected $cm;

    /** @var array */
    protected $messages;

    /** @var mixed */
    protected $course;

    /** @var int */
    protected $currentTime;

    /** @var mod_codiana_renderer */
    protected $renderer;



    public function __construct ($r = null) {
        $this->renderer = $r;
        if ($r)
            $this->init ($r->codiana, $r->cm, $r->context, $r->course, $r->messages);
    }



    protected function init ($codiana, $cm, $context, $course, $messages = array ()) {
        $this->codiana     = $codiana;
        $this->cm          = $cm;
        $this->context     = $context;
        $this->messages    = $messages;
        $this->course      = $course;
        $this->currentTime = time ();
    }



    protected function view_title ($title, $h = 'h3') {
        return html_writer::tag ($h, $title, $this->classMain);
    }



    protected function write_note ($note) {
        return html_writer::tag ('div', html_writer::tag ('em', $note));
    }



    protected function prepare_table ($class = '') {
        $table                      = new html_table();
        $table->attributes['class'] = "generaltable $class";
        $table->attributes['style'] = 'width: 100%';
        $table->head                = array ();
        $table->align               = array ();
        $table->size                = array ();
        return $table;
    }



    protected function add_column ($table, $name = '', $size = '', $align = 'left') {
        $table->head[]  = $name;
        $table->size[]  = $size;
        $table->align[] = $align;
    }



    protected function add_form_start ($action = './view.php', $attrs = null) {
        $attrs = is_null ($attrs) ? array ('id' => $this->cm->id) : $attrs;
        $url   = codiana_create_url ($action, $attrs);

        $formAttr = array (
            'action' => $url,
            'method' => 'post'
        );
        return html_writer::start_tag ('form', $formAttr);
    }



    protected function view_description ($title) {
        $output = $this->view_title ($title);
        if ($this->codiana->intro)
            $output .= $this->renderer->box (format_module_intro ('codiana', $this->codiana, $this->cm->id), 'generalbox mod_introbox boxaligncenter', 'intro');
        else
            $output .= $this->write_note (codiana_string ('message:no_description'));
        return $output;
    }



    protected function add_form_end () {
        return html_writer::end_tag ('form');
    }



    protected function add_submit ($name) {
        return html_writer::empty_tag ('input', array ('type' => 'submit', 'name' => $name, 'value' => codiana_string ($name)));
    }



    protected function add_cancel ($name, $cancelURL = null) {
        return html_writer::link ($cancelURL, codiana_string ($name));
    }



    protected function add_btns ($submit, $cancel, $cancelURL = null) {
        $output = html_writer::start_div ('btn-holder');
        $output .= $this->add_submit ($submit);
        $output .= $this->add_cancel ($cancel, $cancelURL);
        $output .= html_writer::end_div ();
        return $output;
    }



    protected function create_form_checkbox ($userid) {
        $attrs = array (
            'type'    => 'checkbox',
            'name'    => 'userid[]',
            'value'   => $userid,
            'checked' => 'checked'
        );
        return html_writer::empty_tag ('input', $attrs);
    }



    protected function create_input ($type, $name = null, $value = null, $details = array ()) {
        switch ($type) {
            case 'submit':
                return html_writer::empty_tag (
                    'input',
                    array (
                          'type'  => 'submit',
                          'name'  => $name,
                          'value' => codiana_string ($name)
                    ));
            case 'checkbox':
                return html_writer::empty_tag (
                    'input', array (
                                   'type'    => 'checkbox',
                                   'name'    => $name,
                                   'value'   => $value,
                                   'checked' => isset ($details['checked']) ? $details['checked'] : 'checked'
                             ));
            case 'number':
                return html_writer::empty_tag (
                    'input',
                    array (
                          'type'  => 'number',
                          'name'  => $name,
                          'value' => $value,
                          'step'  => isset ($details['step']) ? $details['step'] : 1,
                          'min'   => isset ($details['min']) ? $details['min'] : 0,
                          'max'   => isset ($details['max']) ? $details['max'] : 100
                    ));
            case 'text':
            case 'textarea':
                return html_writer::tag (
                    'textarea',
                    $value,
                    array ('name' => $name));
            case 'select':
                $output = '';
                $output .= html_writer::start_tag ('select', array ('name' => $name));
                foreach (codiana_attempt_state::$state as $n => $v) {
                    $attrs = array ('value' => $n);
                    if ($n == $value) $attrs['selected'] = 'selected';
                    $output .= html_writer::tag ('option', codiana_attempt_state::get ($n), $attrs);
                }
                $output .= html_writer::end_tag ('select');
                return $output;

        }
    }



    /**
     * @param $code
     * @return string
     */
    protected function view_code ($code) {
        $code = trim ($code);
        if (empty ($code))
            return "";

        $lines = preg_split ("/(\r\n|\n|\r)/", $code);
        if (!empty ($lines[sizeof ($lines) - 1])) $lines[] = "&nbsp;";

        $output = '';
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
}



abstract class codiana_table_renderer_base extends codiana_renderer_base {


    protected function get_value_cell ($name, $value, $item = null) {
        return is_null ($value) ? '-' : $value;
    }



    protected function get_name_cell ($name, $section = 'col') {
        return codiana_string ("$section:$name");
    }



    protected function get_time ($value) {
        return is_null ($value) ? '-' : date ('d.m Y (H:i:s)', $value);
    }



    protected function create_property_grid ($names, $values, $nullClass = 'empty') {
        $valuesArray = (array)$values;
        $table       = $this->prepare_table ();
        $this->add_column ($table, '', '25%');
        $this->add_column ($table, '', '75%');
        foreach ($names as $name) {
            $cellValue = $this->get_value_cell ($name, @$valuesArray[$name], $valuesArray);
            $cell      = new html_table_cell($cellValue);
            if (is_null ($cellValue))
                $cell->attributes['class'] = $nullClass;
            $data[] = array (
                $this->get_name_cell ($name),
                $cell
            );
        }
        $table->data = $data;
        return html_writer::table ($table);
    }



    protected function create_table ($names, $values, $nullClass = 'empty') {
        $valuesArray = (array)$values;
        $table       = $this->prepare_table ();
        foreach ($names as $name => $array)
            $this->add_column ($table, $this->get_name_cell ($name), @$array[0], @$array[1]);


        $data = array ();
        foreach ($valuesArray as $value) {
            $row = array ();
            foreach ($names as $name => $array) {
                $item      = (object)$value;
                $cellValue = $this->get_value_cell ($name, @$item->$name, $item);
                $cell      = new html_table_cell($cellValue);
                if (is_null ($cellValue))
                    $cell->attributes['class'] = $nullClass;
                $row[] = $cell;
            }
            $data[] = $row;
        }
        $table->data = $data;
        return html_writer::table ($table);
    }
}



abstract class codiana_property_table_base extends codiana_table_renderer_base {


    protected function view_property_table ($title, $props = array (), $h = 'h3') {
        $output = $this->view_title (codiana_string ($title), $h);
        return $output . $this->create_property_grid ($props, $this->codiana);
    }



    protected function get_value_cell ($name, $value, $item = null) {
        switch ($name) {
            case 'timeopen':
                return is_null ($value) ? codiana_string ('warning:task_no_start') :
                    codiana_date_detail (date ('d.m Y (H:i:s)', $value), $value);
            case 'timeclose':
                return is_null ($value) ? codiana_string ('warning:task_no_end') :
                    codiana_date_detail (date ('d.m Y (H:i:s)', $value), $value);
            case 'timemodified':
            case 'timecreated':
                return codiana_date_detail (date ('d.m Y (H:i:s)', $value), $value);
            case 'plagscheckstate':
                return codiana_plag_state::get ($value);
            case 'outputmethod':
                return codiana_output_method::get ($value);
            case 'grademethod':
                return codiana_grade_method::get ($value);
            case 'state':
                return codiana_task_state::get ($value);
            case 'limittime':
                $f = @$this->codiana->limittimefalling;
                $n = @$this->codiana->limittimenothing;
                return is_null ($f) || is_null ($n) ?
                    codiana_string ('warning:no_limittime_set') :
                    "$f - $n ms";
            case 'limitmemory':
                $f = @$this->codiana->limitmemoryfalling;
                $n = @$this->codiana->limitmemorynothing;
                return is_null ($f) || is_null ($n) ?
                    null :
                    sprintf ("%1.3f - %1.3f MB", $f / 1000, $n / 1000);
            case 'difficulty':
                return str_repeat ('*', $value);
            default:
                return is_null ($value) ? '-' : $value;
        }
    }

}



/**
 * Class codiana_attempt_edit_table
 * Editing one attemp
 */
class codiana_attempt_edit_table extends codiana_table_renderer_base {

    public function view_page ($attempt) {
        $output = $this->view_title (
            codiana_string ('title:edit_attempt')
        );
        $attrs  = array ('id' => $this->cm->id, 'attemptid' => $attempt->id);
        $names  = array ('resultfinal', 'resultnote', 'state');

        $output .= $this->add_form_start ('./editresult.php', $attrs);
        $output .= $this->create_property_grid ($names, $attempt);
        $output .= $this->add_btns ('btn:edit', 'btn:back', codiana_create_moodle_url ('viewresults.php', $attrs));
        $output .= $this->add_form_end ();
        return $output;
    }



    protected function get_value_cell ($name, $value, $item = null) {
        switch ($name) {
            case 'resultfinal';
                return $this->create_input ('number', $name, $value);
            case 'resultnote';
                return $this->create_input ('text', $name, $value);
            case 'state';
                return $this->create_input ('select', $name, $value);
            default:
                parent::get_value_cell ($name, $value, $item);
        }
    }
}



/**
 * Class codiana_user_grade_table
 * User's grade overview
 */
class codiana_user_grade_table extends codiana_table_renderer_base {

    public function view_page ($grade) {
        $output = $this->view_title (codiana_string ('title:user_grade'));
        if (is_null (@$grade->grade))
            return $output . $this->write_note (codiana_string ('message:no_grade_yet'));
        $names = array ('str_long_grade', 'dategraded');
        $output .= $this->create_property_grid ($names, $grade);
        return $output;
    }



    protected function get_value_cell ($name, $value, $item = null) {
        switch ($name) {
            case 'dategraded':
                return codiana_date_detail (date ('d.m Y (H:i:s)', $value), $value);
            case 'str_long_grade':
                return is_null ($value) ? null : $value;
            default:
                parent::get_value_cell ($name, $value, $item);
        }
    }
}



/**
 * Class codiana_task_grade_table
 * Manager's grading table
 */
class codiana_task_grade_table extends codiana_table_renderer_base {

    public function view_page ($grades, $users, $attempts) {
        $names = array (
            'id'              => array ('10', 'center'),
            'username'        => array ('25%', 'left'),
            'current_grade'   => array ('25%', 'center'),
            'finalresult'     => array ('15%', 'center'),
            'suggested_grade' => array ('35%', 'right')
        );

        $values = array ();
        foreach ($grades->items[0]->grades as $userid => $usergrade) {
            $attemptGrade   = codiana_find_by_prop ($attempts, array ('userid' => $userid));
            $plagResult     = is_null ($attemptGrade) ? null : $attemptGrade->plagresult;
            $attemptGrade   = is_null ($attemptGrade) ? null : $attemptGrade->resultfinal;
            $gradeBookGrade = is_null ($usergrade->grade) ? null : $usergrade->str_grade;

            $colID        = $this->create_input ('checkbox', 'userid[]', $userid);
            $colUser      = $users[$userid]->username;
            $colResult    = is_null ($attemptGrade) ? codiana_string ('warning:no_results') : intval ($attemptGrade);
            $colCurrGrade = is_null ($gradeBookGrade) ? codiana_string ('warning:no_grade') : intval ($gradeBookGrade);
            $colSuggGrade = $this->create_input_grade ($userid, $attemptGrade, $plagResult);

            $values[] = array (
                'id'            => $colID, 'username' => $colUser, 'finalresult' => $colResult,
                'current_grade' => $colCurrGrade, 'suggested_grade' => $colSuggGrade);
        }

        $output = $this->view_title (codiana_string ('title:grades'));
        $output .= $this->add_form_start ('./dograde.php');
        $output .= $this->create_table ($names, $values);
        $attrs = array ('id' => $this->cm->id);
        $output .= $this->add_btns ('btn:edit', 'btn:back', codiana_create_moodle_url ('viewresults.php', $attrs));
        $output .= $this->add_form_end ();

        return $output;
    }



    private function create_input_grade ($userid, $attemptGrade, $plagResult) {
        $grade = is_null ($attemptGrade) ? null : $attemptGrade;
        $item  = $this->create_input ('number', "grade_$userid", is_null ($grade) ? 0 : intval ($grade));
        if (!$plagResult)
            return $item;

        $output = '';
        if ($plagResult) {
            $warning = codiana_string ('warning:similarityx', $plagResult);
            $output .= html_writer::tag ('span', $warning, array ('class' => 'warning', 'title' => $warning));
            $output .= html_writer::empty_tag ('img', array ('src' => './html/img/warning.png', 'title' => $warning));
        }
        return $output . $item;
    }
}



/**
 * Class codiana_task_not_active_detail
 * Not active task view details
 */
class codiana_task_not_active_detail extends codiana_property_table_base {

    public function view_page ($grade = null) {
        $output = '';
        $output .= $this->view_title (codiana_string ('title:task_detail') . ' ' . $this->codiana->name, 'h1');
        $output .= $this->view_description (codiana_string ('title:description'));


        if (!codiana_is_task_active ($this->codiana))
            $output .= $this->write_note (codiana_string ('taskstate:task_not_active'));
        else if (codiana_has_task_ended ($this->codiana))
            $output .= $this->write_note (codiana_string ('taskstate:task_has_ended'));
        else
            $output .= $this->write_note (codiana_string ('taskstate:task_not_started'));

        if (codiana_has_task_ended ($this->codiana)) {
            $output .= $this->view_grade ($grade);
        }


        $props = array ('state', 'timeopen', 'timeclose');
        $output .= $this->view_property_table ('title:task_status', $props);

        return $output;
    }



    protected function view_grade ($grade) {
        if (!is_null ($grade)) {
            $renderer = new codiana_user_grade_table ($this->renderer);
            return $renderer->view_page ($grade);
        }
        return '';
    }

}



/**
 * Class codiana_task_detail
 * Active task view details
 */
class codiana_task_detail extends codiana_property_table_base {


    public function view_page ($isManager = false, $grade = null, $attempt = null) {
        $output = '';
        $output .= $this->view_title (codiana_string ('title:task_detail') . ' ' . $this->codiana->name, 'h1');
        $output .= $this->view_description (codiana_string ('title:description'));
        $output .= $this->write_languages_warning ();
        $output .= $this->view_grade ($grade);
        $output .= $this->view_attempt ($attempt);

        $props = array ('languages', 'difficulty', 'outputmethod', 'grademethod');
        $output .= $this->view_property_table ('title:general_info', $props);

        $props = array ('state', 'timeopen', 'timeclose');
        $output .= $this->view_property_table ('title:task_status', $props);

        $props = array ('maxattempts', 'maxusers', 'limittime', 'limitmemory');
        $output .= $this->view_property_table ('title:task_limits', $props);

        $props = array ('id', 'timemodified', 'timecreated', 'plagscheckstate');
        if ($isManager)
            $output .= $this->view_property_table ('title:task_advanced', $props);

        $output .= $this->view_title (codiana_string ('inputexample'));
        $output .= $this->view_code ($this->codiana->inputexample);
        $output .= $this->view_title (codiana_string ('outputexample'));
        $output .= $this->view_code ($this->codiana->outputexample);

        return $output;
    }



    protected function view_grade ($grade) {
        if (!is_null ($grade)) {
            $renderer = new codiana_user_grade_table ($this->renderer);
            return $renderer->view_page ($grade);
        }
        return '';
    }



    protected function view_attempt ($attempt) {
        if (!is_null ($attempt)) {
            $renderer = new codiana_myattempts_table ($this->renderer);
            return $renderer->view_page ($attempt, array ());
        }
        return '';
    }



    protected function write_languages_warning () {
        $output = $this->view_title (codiana_string ('warning:languages_notice'), 'h4');
        return $output . $this->write_note (codiana_string ('warning:main_filename_x', $this->codiana->mainfilename));
    }
}



/**
 * Class codiana_myattempts_table
 * User's attempts view
 */
class codiana_myattempts_table extends codiana_table_renderer_base {


    public function view_page ($gradeAttempt, $otherAttempts) {
        $output = '';
        $output .= $this->view_grade_attempt ($gradeAttempt);
        $output .= $this->view_other_attempts ($otherAttempts, $gradeAttempt);
        return $output;
    }



    protected function view_grade_attempt ($gradeAttempt) {
        $output = $this->view_title (codiana_string ('title:grading_attempt'));

        if ($gradeAttempt != null) {
            // render grade attempt
            $keys = array_keys ((array)$gradeAttempt);
            $keys = codiana_remove_keys ($keys, array ('id', 'ordinal', 'taskid', 'userid'));
            $output .= $this->create_property_grid ($keys, $gradeAttempt);
        } else {
            $output .= $this->write_note (codiana_string ('warning:no_grade_attempt'));
        }
        return $output;
    }



    protected function view_other_attempts ($otherAttempts, $gradeAttempt) {
        $output = '';
        if (!empty ($otherAttempts)) {
            // remove grade attempt
            if ($gradeAttempt != null && array_key_exists ($gradeAttempt->id, $otherAttempts))
                unset ($otherAttempts[$gradeAttempt->id]);

            // empty after grade is gone
            if (empty ($otherAttempts))
                return '';

            $first = reset ($otherAttempts);
            $output .= $this->view_title (codiana_string ('title:all_attempts'));
            $keys = array_keys ((array)$first);
            $keys = codiana_remove_keys ($keys, array ('id', 'ordinal', 'taskid', 'userid'));
            $keys = codiana_remove_keys ($keys, array ('resultnote'));
            $keys = array_combine ($keys, $keys);
            $output .= $this->create_table ($keys, $otherAttempts);
        }
        return $output;
    }



    protected function get_value_cell ($name, $value, $attempt = null) {
        $item = (object)$attempt;
        switch ($name) {

            case 'detail':
                return $value == 0 ? codiana_string ('taskdetail:simple') : codiana_string ('taskdetail:complex');

            case 'timesent':
                $date = codiana_format_dates_message ($value, $this->currentTime);
                return html_writer::tag ('span', $date, array ('title' => date ('d.m Y (H:i:s)', $value)));

            case 'state':
                return codiana_attempt_state::get ($value);
            case 'code':
                return html_writer::link (
                    new moodle_url(
                        '/mod/codiana/sourcecode.php',
                        array (
                              'id'      => $this->cm->id,
                              'userid'  => $item->userid,
                              'ordinal' => $item->ordinal
                        )
                    ), codiana_string ('btn:download'));
            case 'plags':
                $url = new moodle_url(
                    '/mod/codiana/checkplags.php',
                    array (
                          'id'        => $this->cm->id,
                          'userid'    => $item->userid,
                          'attemptid' => $item->id
                    )
                );
                if ($item->plagscheckresult == 0)
                    $similar = codiana_string ('plagstate:no_dupes_found');
                else {
                    $similar = codiana_string ('message:x_similar_solutions_x', $item->plagscheckresult, $item->plagresult);
                }
                $similar     = $similar . '<br />';
                $checkedDate = is_null ($item->plagstimecheck) ? null : codiana_format_dates_message ($item->plagstimecheck);
                $checked     = is_null ($checkedDate) ? '' : '<br />' . codiana_string ('message:checked_x', $checkedDate);

                switch ($item->plagscheckstate) {
                    case codiana_plag_state::PROCESS_ABORTED:
                    case codiana_plag_state::NOT_EXECUTED:
                        return $similar . html_writer::link ($url, codiana_string ('btn:check')) . $checked;
                    case codiana_plag_state::WAITING_TO_PROCESS:
                        return $similar . codiana_string ('plagstate:check_in_progress') . ' (' . html_writer::link ($url, codiana_string ('btn:check_again')) . ')' . $checked;
                    case codiana_plag_state::PLAGS_FOUND:
                    case codiana_plag_state::NO_PLAGS_FOUND:
                        return $similar . html_writer::link ($url, codiana_string ('btn:check_again')) . $checked;
                }
            case 'ordinal':
                $url = new moodle_url(
                    '/mod/codiana/editresult.php',
                    array (
                          'id'        => $this->cm->id,
                          'attemptid' => $item->id
                    ));
                return html_writer::link ($url, codiana_string ('btn:edit'));

            case 'resultnote':
                return is_null ($value) ? '-' : $value;

            default:
                return is_null ($value) ? '-' : $value;
        }
    }
}



/**
 * Class codiana_allattempts_table
 * Teacher's attempts and plags view
 */
class codiana_allattempts_table extends codiana_myattempts_table {


    public function view_page2 ($attempts, $showAll, $plags) {
        $output = $this->view_title (codiana_string ($showAll ? 'title:all_attempts' : 'title:grading_attempts'));
        if (!empty($attempts)) {
            $first = reset ($attempts);
            $keys  = array_keys ((array)$first);
            $keys  = codiana_remove_keys ($keys, array ('id', 'taskid', 'userid'));
            $keys  = codiana_remove_keys ($keys, array ('resultnote', 'plagresult', 'plagscheckresult', 'plagscheckstate', 'plagstimecheck', 'detail'));
            $extra = $showAll ? array ('code') : array ('code', 'plags');
            $keys  = array_merge ($keys, $extra);
            $keys  = array_combine ($keys, $keys);
            $output .= $this->create_table ($keys, $attempts);
        } else
            $output .= $this->write_note (codiana_string ('warning:no_results'));
        $output .= $this->get_attempts_option ($showAll);

        $output .= $this->view_title (codiana_string ('title:plags_result'));
        if (!empty($plags)) {
            $first = reset ($plags);
            $keys  = array_keys ((array)$first);
            $keys  = codiana_remove_keys ($keys, array ('id', 'firstid', 'secondid', 'taskid'));
            $keys  = array_combine ($keys, $keys);
            $output .= $this->create_table ($keys, $plags);
        } else
            $output .= $this->write_note (codiana_string ('warning:no_results'));
        $output .= $this->get_plags_option ();

        return $output;
    }



    protected function get_value_cell ($name, $value, $attempt = null) {
        switch ($name) {
            case 'result':
                return $value;
            default:
                return parent::get_value_cell ($name, $value, $attempt);
        }
    }



    protected function get_plags_option () {
        $url         = new moodle_url('/mod/codiana/checkplags.php', array ('id' => $this->cm->id));
        $checkedDate = is_null ($this->codiana->plagstimecheck) ? null : codiana_format_dates_message ($this->codiana->plagstimecheck);
        $checked     = is_null ($checkedDate) ? '' : ' '.codiana_string ('message:checked_x', $checkedDate);

        switch ($this->codiana->plagscheckstate) {
            case codiana_plag_state::PROCESS_ABORTED:
            case codiana_plag_state::NOT_EXECUTED:
                return html_writer::link ($url, codiana_string ('btn:detect_dupes')) . $checked;
            case codiana_plag_state::WAITING_TO_PROCESS:
                return codiana_string ('btn:detect_dupes_in_progress') . ' (' . html_writer::link ($url, codiana_string ('btn:check_again')) . ') ' . $checked;
            case codiana_plag_state::PLAGS_FOUND:
                return html_writer::link ($url, codiana_string ('btn:detect_dupes_again')) . $checked;
            case codiana_plag_state::NO_PLAGS_FOUND:
                return html_writer::link ($url, codiana_string ('btn:no_dupes_found_again')) . $checked;
            default:
                return '';
        }
    }



    protected function get_attempts_option ($showAll) {
        $url = new moodle_url('/mod/codiana/viewresults.php', array ('id' => $this->cm->id, 'all' => $showAll ? '0' : '1'));
        return $showAll ? html_writer::link ($url, codiana_string ('btn:show_grades_results')) : html_writer::link ($url, codiana_string ('btn:show_all_results'));

    }
}



class codiana_stats_renderer extends codiana_renderer_base {

    public $width = 320;

    public $height = 240;



    public function view_page ($stats) {
        $output    = $this->view_title (codiana_string ("title:stats"));
        $jscontent = 'var ctx, leg, data, config' . "\n";
        $jscontent .= 'config = {
        segmentShowStroke: !0, segmentStrokeColor: "#FFF",
        segmentStrokeWidth: 1, percentageInnerCutout: 45,
        animation: !0, animationSteps: 100,
         animationEasing: "easeInOutExpo",
         animateRotate: 1, animateScale:!1, onAnimationComplete: null}';
        $id        = 0;
        $hasData   = false;
        foreach ($stats as $name => $stat) {
            $stat = (object)$stat;
            if (empty($stat->data))
                continue;

            $hasData = true;
            $id++;
            $output .= html_writer::tag ('h4', codiana_string ("title:$name"), array ('class' => 'codiana_chart_title'));
            $output .= "<div class=\"codiana_chart\"><canvas id=\"chart$id\" width=\"$this->width\" height=\"$this->height\"></canvas><div class=\"codiana_chart_legend\" id=\"legend$id\"></div></div>";

            $jscontent .= '
            // ---' . $name . '---
            ctx = document.getElementById ("chart' . $id . '").getContext ("2d");
            leg = document.getElementById ("legend' . $id . '");
            data = ' . $this->get_data ($stat, $name) . ';
            new Chart (ctx).Doughnut (data, config);
            legend (leg, data);' . "\n\n\n";
        }
        if (!$hasData)
            return $output . $this->write_note (codiana_string ('warning:no_results'));
        //
        $js = '<script type="text/javascript">' . "\n\n$jscontent\n\n" . '</script>';
        return $output . $js;
    }



    protected function  get_data ($stat, $name) {
        $colors = array (
            '#5B9BFF', '#ED7D31', '#FFC000', '#4472C4', '#707147',
            '#255E91', '#9E480E', '#636363', '#997300', '#264478',
            '#43682B', '#7CAFDD', '#F1975A', '#990000', '#000099',
        );
        $data   = array ();
        $i      = 0;
        foreach ($stat->data as $item) {
            $data[] = sprintf (
                '{value: %s, color: "%s", title: "%s"}',
                $item->total,
                $colors[$i++],
                $this->get_legend ($name, $item->total, $item->result, $item->result + $stat->res));
        }
        return '[' . (implode (',', $data)) . ']';
    }



    protected function get_legend ($name, $count, $from, $to) {
        switch ($name) {
            case 'state_stat':
                return sprintf ('%s (%s×)', codiana_attempt_state::get ($from), $count);
            case 'resultfinal_stat':
                $to   = min ($to, 100);
                $from = max ($from, 0);
//                $from = $from == $to ? $from-1 : $from;
                if ($from == $to)
                    return codiana_string ('legend:resultfinal_x', $from, $count);
                return codiana_string ('legend:resultfinal', $from, $to, $count);
            case 'time_stat':
                $from = max ($from, 0);
                if ($from == $to)
                    return codiana_string ('legend:time_x', $from, $count);
                return codiana_string ('legend:time', $from, $to, $count);
            case 'memory_stat':
                $from = max ($from, 0);
                if ($from == $to)
                    return codiana_string ('legend:memory_x', $from, $count);
                return codiana_string ('legend:memory', $from, $to, $count);
        }
    }
}