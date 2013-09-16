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

        public function to_db_format() {
            switch ($this->type) {
                case 'StringField':
                case 'TextField':
                case 'DateTimeField':
                    return '"'. $this->value .'"';
                    break;
                case 'PasswordField':
                    return Auth::get_hash($this->value);
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
            $class_name = $this->model;
            $object = $class_name::get($this->value);
            return $object ? (string)$object->name : '';
        }
    }

    // @todo: choices

?>