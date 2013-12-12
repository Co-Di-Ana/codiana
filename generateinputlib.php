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

abstract class abstract_generator extends stdClass {

    /** @var string */
    protected $id;

    /** @var string */
    protected $format;



    public function __construct ($id, $format = '%s') {
        $this->id = $id;
        $this->format = is_int ($format) ? '%0' . $format . 'd' : $format;
    }



    /**
     * @return mixed
     */
    public function value () {
        return empty ($this->format) ? $this->getNext () : sprintf ($this->format, $this->getNext ());
    }



    /**
     * @return string
     */
    public function toString () {
        return "[$this->id]";
    }



    abstract protected function getNext ();
}



class generator_variable_random extends abstract_generator {

    /** @var int */
    private $min;

    /** @var int */
    private $max;



    public function __construct ($id, $min, $max, $format) {
        parent::__construct ($id, $format);
        $this->min = $min;
        $this->max = $max;
    }



    public function getNext () {
        return rand ($this->min, $this->max);
    }
}



class generator_variable_step extends abstract_generator {

    /** @var int */
    private $start;

    /** @var int */
    private $step;

    /** @var int */
    private $counter;



    public function __construct ($id, $start, $step, $format) {
        parent::__construct ($id, $format);
        $this->start = $start;
        $this->counter = $start;
        $this->step = $step;
    }



    public function reset () {
        $this->counter = $this->start;
    }



    public function getNext () {
        $result = $this->counter;
        $this->counter = $this->counter + $this->step;
        return $result;
    }
}



class generator_section extends abstract_generator {

    /** @var array */
    private $items;



    public function __construct ($id, $count, $items) {
        parent::__construct ($id);
        $this->items = $items;
        $this->count = intval ($count);
    }



    public function add ($item) {
        $this->items[] = $item;
    }



    public function getNext () {
        $output = '';
        foreach ($this->items as $item) {
            if (method_exists ($item, 'reset')) {
                $item->reset ();
            }
        }
        for ($i = 0; $i < $this->count; $i++) {
            foreach ($this->items as $item) {
                $output .= $item->value ();
            }
        }
        return $output;
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



class generator_string extends abstract_generator {


    public function __construct ($id) {
        parent::__construct ($id, '%s');
    }



    public function getNext () {
        return $this->id;
    }
}



class generator_new_line extends abstract_generator {

    public function __construct () {
        parent::__construct ('\n');
    }



    public function toString () {
        return "[\\n]</li><li>";
    }



    public function getNext () {
        return "\n";
    }
}



class generator_parser extends stdClass {

    private function __construct () {
    }



    public static function create ($rawJson) {
        $data = json_decode ($rawJson);
        if (json_last_error () != JSON_ERROR_NONE)
            return false;
        return generator_parser::parse ($data);
    }



    private static function parse ($json) {
        switch ($json->core->type) {
            case 'variable-step':
                return new generator_variable_step ($json->core->id, $json->start, $json->step, $json->core->format);
            case 'variable-random':
                return new generator_variable_random ($json->core->id, $json->min, $json->max, $json->core->format);
            case 'string':
                return new generator_string ($json->core->id);
            case 'new-line':
                return new generator_new_line ();
            case 'section':
                $section = new generator_section ($json->core->id, $json->count, array ());
                foreach ($json->items as $item)
                    $section->add (generator_parser::parse ($item));
                return $section;
            default:
                // TO-DO exception
                return null;
        }
    }
}