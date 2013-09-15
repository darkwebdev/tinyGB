<?

    abstract class Field {
        protected $name;
        protected $value;
        protected $default_value;
        protected $type;
        protected $editable = true;

        public function __construct($name=null) {
            $this->type = get_called_class();
            if ($name) $this->name = $name;
//            var_dump($this);
//            var_dump($editable);
            //var_dump(xdebug_get_function_stack());
        }

        public function __get($prop) {
            $value = null;
            if (property_exists($this, $prop)) {
                $value = $this->$prop;
            }

            return $value;
        }

        public function __set($property, $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }

            // @todo: add additional checks
            return $this;
        }

        public function __toString() {
            return (string)$this->value;
        }

        public function get_db_value() {
            switch ($this->type) {
                case 'StringField':
                case 'TextField':
                case 'DateTimeField':
                    return '"'. $this->value .'"';
                    break;
                case 'BoolField':
                    return $this->value ? 1 : 0;
                    break;
                default:
                    return $this->value;
            }
        }

    }

    class BoolField extends Field {
        protected $default_value = false;
    }

    class StringField extends Field {
        protected $default_value = '';
    }

    class TextField extends StringField {
    }

    class DateTimeField extends StringField {
        protected $default_value = '01-01-1970';
    }

    class NumberField extends Field {
        protected $default_value = 0;
    }

    // @todo: choices

?>