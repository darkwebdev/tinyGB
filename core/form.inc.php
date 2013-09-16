<?
    include_once('model.inc.php');

    class FormField {

        public function __construct($data) {
            $this->fill_data($data);
        }

        private function fill_data($data) {
            foreach ($data as $field => $value) {
                $this->{$field} = $value;
            }
        }
    }


    class Form {
        public $fields = [];
        protected static $field2input = [
            'TextField' => 'textarea',
            'StringField' => 'text',
            'NumberField' => 'number',
            'AutoIncrementField' => 'number',
            'DateTimeField' => 'text',
            'BoolField' => 'checkbox',
            'ForeignKey' => 'text' // @todo: must be select
        ];

        function __construct($object) {
            foreach(get_object_vars($object) as $field_name => $field) {
//                var_dump($object);
                if (!$field->readonly) {
                    $this->fields[] = new FormField([
                        'name' => $field_name,
                        'label' => $field->name ? $field->name : ucfirst($field_name),
                        'value' => $field->value ? $field->value : ($field->default_value ? $field->default_value : ''),
                        'type' => $this->get_input_type($field->type),
                        'editable' => $field->editable
                    ]);
                }
            }
//            var_dump($this->fields);
        }

        private function get_input_type($field_type) {
            return self::$field2input[$field_type];
        }

        public function as_array($show_all=false) {
            $object_array = [];
            foreach ($this->fields as $field => $value) {
                if ($show_all || $field->editable) {
                    $object_array[$field] = (string)$value;
                }
            }
            return $object_array;
        }

    }
?>