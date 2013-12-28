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

defined ('MOODLE_INTERNAL') || die();

abstract class codiana_abstract_generator extends stdClass {

    /** @var string */
    protected $id;

    /** @var string */
    protected $format;

    /** @var resource */
    protected $handle;



    public function __construct ($handle, $id, $format = '%s') {
        $this->id     = $id;
        $this->handle = $handle;
        $this->format = is_int ($format) ? '%0' . $format . 'd' : $format;
    }



    /**
     * @return bool
     */
    public function value () {
        if (!codiana_generator_parser::canWrite ())
            return false;

        codiana_generator_parser::addBytes (
            fwrite ($this->handle, empty ($this->format) ? $this->getNext () : sprintf ($this->format, $this->getNext ()))
        );
        return true;
    }



    /**
     * @return string
     */
    public function toString () {
        return "[$this->id]";
    }



    abstract protected function getNext ();
}



class codiana_generator_variable_random extends codiana_abstract_generator {

    /** @var int */
    private $min;

    /** @var int */
    private $max;



    public function __construct ($handle, $id, $min, $max, $format) {
        parent::__construct ($handle, $id, $format);
        $this->min = $min;
        $this->max = $max;
    }



    public function getNext () {
        return rand ($this->min, $this->max);
    }
}



class codiana_generator_variable_step extends codiana_abstract_generator {

    /** @var int */
    private $start;

    /** @var int */
    private $step;

    /** @var int */
    private $counter;



    public function __construct ($handle, $id, $start, $step, $format) {
        parent::__construct ($handle, $id, $format);
        $this->start   = $start;
        $this->counter = $start;
        $this->step    = $step;
    }



    public function reset () {
        $this->counter = $this->start;
    }



    public function getNext () {
        $result        = $this->counter;
        $this->counter = $this->counter + $this->step;
        return $result;
    }
}



class codiana_generator_section extends codiana_abstract_generator {

    /** @var array */
    private $items;



    public function __construct ($handle, $id, $count, $items) {
        parent::__construct ($handle, $id);
        $this->items = $items;
        $this->count = intval ($count);
    }



    public function add ($item) {
        $this->items[] = $item;
    }



    public function getNext () {
        foreach ($this->items as $item) {
            if (method_exists ($item, 'reset')) {
                $item->reset ();
            }
        }
        for ($i = 0; $i < $this->count; $i++) {
            foreach ($this->items as $item) {
                if ($item->value () == false)
                    return;
            }
        }
    }



    /**
     * @return bool
     */
    public function value () {
        // generate fields
        $this->getNext ();
        return true;
    }



    public function toString () {
        $output = "<li>$this->id</li>";
        $output .= '<ul><li>';
        foreach ($this->items as $item) {
            $output .= $item->toString ();
        }
        $output .= '</li>';
        $output .= '</ul>';
        $output = str_replace ('<li></li>', '', $output);
        return $output;
    }



    public function getString () {
        return '<ul>' . $this->toString () . '</ul>';
    }
}



class codiana_generator_string extends codiana_abstract_generator {


    public function __construct ($handle, $id) {
        parent::__construct ($handle, $id, '%s');
    }



    public function getNext () {
        return $this->id;
    }
}



class codiana_generator_new_line extends codiana_abstract_generator {

    public function __construct ($handle) {
        parent::__construct ($handle, '\n');
    }



    public function toString () {
        return "[\\n]</li><li>";
    }



    public function getNext () {
        return "\n";
    }
}


define ('CODIANA_GENERATOR_MAX_FILE_SIZE', 1 * 1024 * 1024 * 10);

class codiana_generator_parser extends stdClass {

    /** @var int */
    private static $error;

    /** @var stdClass */
    private static $data;

    /** @var int */
    private static $bytes;

    /** @var string */
    public static $json;


    /** @var int maximum file size (currently 10MB) */
    const MAX_FILE_SIZE = CODIANA_GENERATOR_MAX_FILE_SIZE;



    private function __construct () {
    }



    public static function addBytes ($value) {
        if ($value == false)
            throw new Exception ('cannot write to file');
        self::$bytes += $value;
    }



    public static function getSize () {
        return self::$bytes;
    }



    public static function canWrite () {
        return self::$bytes <= self::MAX_FILE_SIZE;
    }



    /**
     * @return mixed
     */
    public static function getError () {
        return self::$error;
    }



    public static function check ($rawJson) {
        self::$json = $rawJson;
        self::$data = json_decode ($rawJson);
        if ((self::$error = json_last_error ()) != JSON_ERROR_NONE)
            return false;
        return true;
    }



    public static function generate ($handle) {
        $mainSection = self::parse ($handle, self::$data);
        $mainSection->value ();

        return self::canWrite ();
    }



    private static function parse ($handle, $json) {
        switch ($json->core->type) {
            case 'variable-step':
                return new codiana_generator_variable_step ($handle, $json->core->id, $json->start, $json->step, $json->core->format);
            case 'variable-random':
                return new codiana_generator_variable_random ($handle, $json->core->id, $json->min, $json->max, $json->core->format);
            case 'string':
                return new codiana_generator_string ($handle, $json->core->id);
            case 'new-line':
                return new codiana_generator_new_line ($handle);
            case 'section':
                $section = new codiana_generator_section ($handle, $json->core->id, $json->count, array ());
                foreach ($json->items as $item)
                    $section->add (self::parse ($handle, $item));
                return $section;
            default:
                // TO-DO exception
                return null;
        }
    }
}