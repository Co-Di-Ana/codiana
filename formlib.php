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
    public $extension;

    /** @var string */
    public $defaultExtension;

    /** @var stdClass */
    public $codiana;

    /** @var MoodleQuickForm */
    public $mform;

    /** @var moodle_url */
    protected $url;



    public function __construct ($codiana, $action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true) {
        $this->codiana = $codiana;
        $this->url = $action;
        parent::__construct ($action, $customdata, $method, $target, $attributes, $editable);
    }



    public function validate_solution_file ($elementName = 'sourcefile') {
        global $CFG;
        require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
        $solution = (object)$this->get_data ();
        $this->defaultExtension = codiana_solution_get_extension ($this, $elementName);
        $this->extension = $this->defaultExtension == 'zip' ? $solution->language : $this->defaultExtension;

        $languages = trim ($this->codiana->languages);
        $languages = empty($languages) ? array () : explode (',', $this->codiana->languages);

        // unknown extension check
        if (in_array ($this->extension, $languages) == false)
            return 'Unapproved file type \'%s\'';
        return null;
    }



    protected function addTaskFileElement ($fileTypeName, $required, $acceptedTypes = '*') {
        global $CFG;
        $this->mform->addElement (
            'filepicker',
            $fileTypeName,
            get_string ("codiana:$fileTypeName", 'codiana'),
            null,
            array ('maxbytes' => $CFG->maxbytes,
                   'accepted_types' => $acceptedTypes));
        if ($required)
            $this->mform->addRule ($fileTypeName, "you must specify atleast one file", 'required', null, 'client');
        $this->mform->setType ($fileTypeName, PARAM_RAW);
        $this->mform->addHelpButton ($fileTypeName, "codiana:$fileTypeName", 'codiana');
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
        $mform->addElement ('select', 'language', get_string ('codiana:solutionlanguage', 'codiana'), $languages);
        $mform->getElement ('language')->setSelected ($selected);
        $mform->getElement ('language')->setMultiple (false);
        $mform->addRule ('language', null, 'required', null, 'client');
        $mform->addRule ('language', null, 'required', null, 'server');
        $mform->setType ('language', PARAM_ALPHANUMEXT);
        $mform->addHelpButton ('language', 'codiana:solutionlanguage', 'codiana');

        // solution file
        $mform->addElement ('filepicker', 'sourcefile', get_string ('codiana:sourcefile', 'codiana'), null,
                            array (
                                  'maxbytes' => $CFG->maxbytes,
                                  'accepted_types' => '*'
                            )
        );
        $mform->setType ('sourcefile', PARAM_RAW);
        $mform->addRule ('sourcefile', 'you must specify atleast one file', 'required', null, 'client');
        $mform->addRule ('sourcefile', 'you must specify atleast one file', 'required', null, 'server');
        $mform->addHelpButton ('sourcefile', 'codiana:sourcefile', 'codiana');

        $this->add_action_buttons (true, get_string ('codiana:submitsolution:submit', 'codiana'));
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
        global $CFG, $DB;
        $this->mform = $this->_form;

        $this->files = (object)codiana_get_files_status ($this->codiana);

        // task input file
        $this->mform->addElement ('header', 'inputfilesection', get_string ('codiana:section:inputfilesection', 'codiana'));
        $this->mform->addHelpButton ('inputfilesection', 'codiana:section:inputfilesection', 'codiana');
        $this->mform->setExpanded ('inputfilesection');
        $this->addInputOrGenerateElement ();


        // task solution file
        $this->mform->addElement ('header', 'outputfilesection', get_string ('codiana:section:outputfilesection', 'codiana'));
        $this->mform->addHelpButton ('outputfilesection', 'codiana:section:outputfilesection', 'codiana');
        $this->mform->setExpanded ('outputfilesection');
        $this->addOutputOrSolutionElement ();


        $this->add_action_buttons (true);
    }



    private function addOutputOrSolutionElement () {
        // output or solution
        $radioarray = array ();
        $radioarray[] =& $this->mform->createElement ('radio', 'outputorsolution', '', get_string ('codiana:managefiles:output', 'codiana'), codiana_output_type::OUTPUT_FILE);
        $radioarray[] =& $this->mform->createElement ('radio', 'outputorsolution', '', get_string ('codiana:managefiles:solution', 'codiana'), codiana_output_type::SOLUTION);
        $this->mform->addGroup ($radioarray, 'outputorsolutiongroup', get_string ('codiana:managefiles:outputorsolution', 'codiana'), array (' '), false);
        $this->mform->setDefault ('outputorsolution', codiana_output_type::SOLUTION);
        $this->mform->setType ('outputorsolution', PARAM_INT);
        $this->mform->addHelpButton ('outputorsolutiongroup', 'codiana:outputorsolutionfile', 'codiana');

        // languages
        $languages = codiana_get_task_languages ($this->codiana);
        reset ($languages);
        $selected = key ($languages);

        // solution extension if zip
        $this->mform->addElement ('select', 'language', get_string ('codiana:solutionlanguage', 'codiana'), $languages);
        $this->mform->getElement ('language')->setSelected ($selected);
        $this->mform->getElement ('language')->setMultiple (false);
        $this->mform->setType ('language', PARAM_ALPHANUMEXT);
        $this->mform->disabledIf ('language', 'outputorsolution', 'checked');
        $this->mform->addHelpButton ('language', 'codiana:solutionlanguage', 'codiana');

        // file picker
        $name = 'outputorsolutionfile';
        $this->addTaskFileElement ($name, false, '*');

        if ($this->files->output)
            $this->addHTML ('By submitting output file, you will override existing output file!', 'warning');
        else
            $this->addHTML ('You need to submit output file in order to be able activate task!', 'error');
    }



    private function addInputOrGenerateElement () {
        $name = 'inputfile';
        $this->addTaskFileElement ($name, false, '*.in');

        if ($this->files->input)
            $this->addHTML ('By submitting input file, you will override existing input file!', 'warning');
        else
            $this->addHTML ('You need to submit input file in order to be able activate task!', 'error');

        $here = html_writer::link ($this->generateInputURL, 'here');
        $this->addHTML ("You can generate input $here.");
    }



    function validation ($data, $files) {
        global $CFG;
        require_once ($CFG->dirroot . '/mod/codiana/locallib.php');
        $errors = parent::validation ($data, $files);

        return $errors;
    }



    public function validateFiles () {

        $data = $this->get_data ();
        $isSolution = @$data->outputorsolution == codiana_output_type::SOLUTION;

        $input = $this->get_new_filename ('inputfile');
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
            $result = codiana_save_input_file ($this->codiana, $this);
            $messages = array_merge ($messages, $result);
        }

        // output was sent
        if ($output !== false && !$isSolution) {
            $result = codiana_save_output_file ($this->codiana, $this);
            $messages = array_merge ($messages, $result);
        }

        // solution was sent
        if ($output !== false && $isSolution) {
            $result = codiana_save_proto_solution ($this->codiana, $this);
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
        global $CFG, $DB;
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
        $object->generateinput = '"csa';

        $result = generator_parser::create ($object->generateinput);
        if ($result == false)
            $errors['generateinput'] = 'wrong value';

        return $errors;
    }
}