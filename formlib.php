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
 * Base class for the settings form for {@link quiz_attempts_report}s.
 *
 * @package   mod_quiz
 * @copyright 2012 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined ('MOODLE_INTERNAL') || die();

require_once ($CFG->libdir . '/formslib.php');


abstract class mod_codiana_solution_file_form extends moodleform {

    /** @var string */
    public $language;

    /** @var string */
    public $isZipped;

    /** @var string */
    public $extension;

    /** @var stdClass */
    public $codiana;

    /** @var MoodleQuickForm */
    public $mform;

    /** @var moodle_url */
    protected $url;



    public function __construct ($codiana, $action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true) {
        $this->codiana = $codiana;
        $this->url     = $action;
        parent::__construct ($action, $customdata, $method, $target, $attributes, $editable);
    }



    public function validate_solution_file ($elementName = 'sourcefile') {
        global $CFG;

        require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
        $solution        = (object)$this->get_data ();
        $this->extension = codiana_solution_get_extension ($this, $elementName);
        $this->language  = $solution->language;
        $this->isZipped  = $this->extension == 'zip';

        $languages = trim ($this->codiana->languages);
        $languages = empty($languages) ? array () : explode (',', $this->codiana->languages);

        // unknown extension check
        if (in_array ($this->language, $languages) == false)
            return sprintf (codiana_string ('error:unsupportedfiletypex'), $this->language);
        return null;
    }



    protected function addTaskFileElement ($fileTypeName, $required, $acceptedTypes = '*') {
        global $CFG;
        $this->mform->addElement (
            'filepicker',
            $fileTypeName,
            codiana_string ("$fileTypeName"),
            null,
            array ('maxbytes'       => $CFG->maxbytes,
                   'accepted_types' => $acceptedTypes));
        if ($required)
            $this->mform->addRule ($fileTypeName, codiana_string ('form:specify_atleast_one_file'), 'required', null, 'client');
        $this->mform->setType ($fileTypeName, PARAM_RAW);
        $this->mform->addHelpButton ($fileTypeName, "$fileTypeName", 'codiana');
    }



    protected function addHTML ($html, $class = '', $elementName = null) {
        if (empty ($elementName))
            return $this->mform->addElement ('html', '
        <div class="fitem ' . $class . '">
            <div class="fitemtitle">&nbsp</div>
            <div class="felement">' . $html . '</div>
        </div>');

        return $this->mform->addElement ('static', $elementName, '', $html);
    }

}



/**
 * Base class for the settings form for {@link quiz_attempts_report}s.
 *
 * @copyright 2012 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_codiana_submitsolution_form extends mod_codiana_solution_file_form {


    /** @var string */
    public $extension;

    /** @var string */
    public $defaultExtension;

    /** @var stdClass */
    public $codiana;



    protected function definition () {
        global $CFG;
        $mform = $this->_form;


        $languages = codiana_get_task_languages ($this->codiana);
        reset ($languages);
        $selected = key ($languages);


        // solution extension if zip
        $mform->addElement ('select', 'language', codiana_string ('solutionlanguage'), $languages);
        $mform->getElement ('language')->setSelected ($selected);
        $mform->getElement ('language')->setMultiple (false);
        $mform->addRule ('language', null, 'required', null, 'client');
        $mform->addRule ('language', null, 'required', null, 'server');
        $mform->setType ('language', PARAM_ALPHANUMEXT);
        $mform->addHelpButton ('language', 'solutionlanguage', 'codiana');

        // solution file
        $mform->addElement ('filepicker', 'sourcefile', codiana_string ('sourcefile'), null,
                            array (
                                  'maxbytes'       => $CFG->maxbytes,
                                  'accepted_types' => '*'
                            )
        );
        $mform->setType ('sourcefile', PARAM_RAW);
        $mform->addRule ('sourcefile', codiana_string ('form:specify_atleast_one_file'), 'required', null, 'client');
        $mform->addRule ('sourcefile', codiana_string ('form:specify_atleast_one_file'), 'required', null, 'server');
        $mform->addHelpButton ('sourcefile', 'sourcefile', 'codiana');

        $this->add_action_buttons (true, codiana_string ('submitsolution:submit'));
    }
}



class mod_codiana_managefiles_form extends mod_codiana_solution_file_form {

    /** @var moodle_url */
    private $generateInputURL;

    /** @var  stdClass */
    private $files;



    public function __construct ($codiana, $action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true) {
        $this->generateInputURL = new moodle_url('/mod/codiana/generateinput.php', $action->params ());
        parent::__construct ($codiana, $action, $customdata, $method, $target, $attributes, $editable);
    }



    protected function definition () {
        $this->mform = $this->_form;

        $this->files = (object)codiana_get_files_status ($this->codiana);

        // task input file
        $this->mform->addElement ('header', 'inputfilesection', codiana_string ('section:inputfilesection'));
        $this->mform->addHelpButton ('inputfilesection', 'section:inputfilesection', 'codiana');
        $this->mform->setExpanded ('inputfilesection');
        $this->addInputOrGenerateElement ();


        // task solution file
        $this->mform->addElement ('header', 'outputfilesection', codiana_string ('section:outputfilesection'));
        $this->mform->addHelpButton ('outputfilesection', 'section:outputfilesection', 'codiana');
        $this->mform->setExpanded ('outputfilesection');
        $this->addOutputOrSolutionElement ();


        $this->add_action_buttons (true);
    }



    private function addOutputOrSolutionElement () {
        // output or solution
        $radioarray   = array ();
        $radioarray[] =& $this->mform->createElement (
            'radio',
            'outputorsolution', '',
            codiana_string ('managefiles:output'),
            codiana_output_type::OUTPUT_FILE);
        $radioarray[] =& $this->mform->createElement (
            'radio',
            'outputorsolution', '',
            codiana_string ('managefiles:solution'),
            codiana_output_type::SOLUTION);

        $this->mform->addGroup (
            $radioarray,
            'outputorsolutiongroup',
            codiana_string ('managefiles:outputorsolution'),
            array (' '), false);

        $this->mform->setDefault ('outputorsolution', codiana_output_type::SOLUTION);
        $this->mform->setType ('outputorsolution', PARAM_INT);
        $this->mform->addHelpButton ('outputorsolutiongroup', 'outputorsolutionfile', 'codiana');

        // languages
        $languages = codiana_get_task_languages ($this->codiana);
        reset ($languages);
        $selected = key ($languages);

        // solution extension if zip
        $this->mform->addElement ('select', 'language', codiana_string ('solutionlanguage'), $languages);
        $this->mform->getElement ('language')->setSelected ($selected);
        $this->mform->getElement ('language')->setMultiple (false);
        $this->mform->setType ('language', PARAM_ALPHANUMEXT);
        $this->mform->disabledIf ('language', 'outputorsolution', 'checked');
        $this->mform->addHelpButton ('language', 'solutionlanguage', 'codiana');

        // file picker
        $name = 'outputorsolutionfile';
        $this->addTaskFileElement ($name, false, '*');

        if ($this->files->output) {
            $this->addHTML (codiana_string ('warning:submiting_will_override'), 'warning');
            $link = new moodle_url('/mod/codiana/download.php', array ('id' => $this->url->param ('id'), 'type' => 'o'));
            $link = html_writer::link ($link, codiana_string ('downloadoutput'));
            $this->mform->addElement ('static', 'name', '', $link);
        } else
            $this->addHTML (codiana_string ('warning:specify_x_before_activate', codiana_string('output_file')), 'error');
    }



    private function addInputOrGenerateElement () {
        $name = 'inputfile';
        $this->addTaskFileElement ($name, false, '*.in');

        if ($this->files->input) {
            $this->addHTML (codiana_string ('warning:submiting_will_override'), 'warning');
            $link = new moodle_url('/mod/codiana/download.php', array ('id' => $this->url->param ('id'), 'type' => 'i'));
            $link = html_writer::link ($link, codiana_string ('downloadinput'));
            $this->mform->addElement ('static', 'name', '', $link);
        } else
            $this->addHTML (codiana_string ('warning:specify_x_before_activate', codiana_string('input_file')), 'error');

        $here = html_writer::link ($this->generateInputURL, codiana_string ('btn:here'));
        $this->addHTML (codiana_string ('message:generate_input_x', $here));
    }



    function validation ($data, $files) {
        global $CFG;
        require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
        $errors = parent::validation ($data, $files);

        return $errors;
    }



    public function validateFiles () {

        $data       = $this->get_data ();
        $isSolution = @$data->outputorsolution == codiana_output_type::SOLUTION;

        $input  = $this->get_new_filename ('inputfile');
        $output = $this->get_new_filename ('outputorsolutionfile');

        if ($output !== false) {
            $error = $this->validate_solution_file ('outputorsolutionfile');
            if ($isSolution && $error != null) {
                redirect ($this->url, sprintf ($error, $this->mform->extension));
                die();
            }
        }

        $messages = array ();

        if ($input !== false) {
            // input was sent
            $result   = codiana_save_input_file ($this->codiana, $this);
            $messages = array_merge ($messages, $result);
        }

        // output was sent
        if ($output !== false && !$isSolution) {
            $result   = codiana_save_output_file ($this->codiana, $this);
            $messages = array_merge ($messages, $result);
        }

        // solution was sent
        if ($output !== false && $isSolution) {
            $result   = codiana_save_proto_solution ($this->codiana, $this);
            $messages = array_merge ($messages, $result);
        }

        return $messages;
    }
}



class mod_codiana_generate_input extends moodleform {

    /** @var stdClass */
    private $codiana;

    /** @var MoodleQuickForm */
    private $mform;



    public function __construct ($codiana, $action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true) {
        $this->codiana = $codiana;
        parent::__construct ($action, $customdata, $method, $target, $attributes, $editable);
    }



    protected function definition () {
        $this->mform = $this->_form;

        $this->mform->addElement ('hidden', 'generateinput', '', array ('id' => 'id_generateinput'));
        $this->mform->setType ('generateinput', PARAM_RAW);
        $this->mform->addRule ('generateinput', null, 'required', null, 'client');
        $this->mform->addRule ('generateinput', null, 'required', null, 'server');

        $this->add_action_buttons ();
    }



    public function validation ($data, $files) {
        global $CFG;
        $errors = parent::validation ($data, $files);
        require_once ($CFG->dirroot . '/mod/codiana/generateinputlib.php');
        $object = (object)$data;

        $result = codiana_generator_parser::check ($object->generateinput);
        if ($result == false)
            $errors['generateinput'] = codiana_generator_parser::getError ();
        return $errors;
    }



    public function display () {
        if (!empty($this->mform->_errors))
            echo html_writer::tag ('p', "JSON error format (code {$this->mform->_errors['generateinput']})", array ('class' => 'error'));
        return parent::display ();
    }
}