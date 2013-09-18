<?

    abstract class Field {
        protected $name;
        protected $value;
        protected $default_value;
        protected $type;
        protected $editable = true;
        protected $readonly = false;

        public function __construct($name=null) {
            $this->type = get_called_class();
            if ($name) $this->name = $name;
        }

        public function __get($property) {
            $value = null;
            if (property_exists($this, $property)) {
                $value = $this->$property;
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

        public function to_db_format() {
            switch ($this->type) {
                case 'StringField':
                case 'TextField':
                case 'DateTimeField':
                    return '"'. $this->value .'"';
                    break;
                case 'PasswordField':
                    return '"'. Auth::get_hash($this->value) .'"';
                    break;
                case 'BoolField':
                    return $this->value ? 1 : 0;
                    break;
                case 'AutoIncrementField':
                    return $this->value ? $this->value : 'null';
                    break;
                default:
                    return $this->value;
            }
        }

        public function to_json_format() {
            switch ($this->type) {
                case 'StringField':
                case 'TextField':
                case 'DateTimeField':
                    return (string)$this->value;
                    break;
                case 'PasswordField':
                    return '********';
                    break;
                case 'BoolField':
                    return $this->value > 0 ? true : false;
                    break;
                case 'AutoIncrementField':
                    return intval($this->value);
                    break;
                case 'ForeignKey':
                    return $this->get_related()->as_array();
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

    class PasswordField extends StringField {
        protected $readonly = true;
    }

    class DateTimeField extends StringField {
        protected $default_value = '01-01-1970';
    }

    class NumberField extends Field {
        protected $default_value = 0;
    }

    class AutoIncrementField extends NumberField {
        protected $default_value = null;
        protected $readonly = true;
    }


    class ForeignKey extends Field {
        protected $model;

        public function __toString() {
            $object = $this->get_related();

            return $object ? (string)$object->name : '';
        }

        public function get_related() {
            $class_name = $this->model;

            return $class_name::get(intval($this->value));
        }

        public function set_related($object) {
            $this->value = $object->id;
            ChromePhp::log('<- FK', $object, $this->value);

            return $this;
        }

    }

    // @todo: choices

?>