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

        public function as_array() {
            $field_array = [];
            foreach (get_object_vars($this) as $name => $value) {
                $field_array[$name] = $value;
            }
            return $field_array;
        }
    }


    abstract class Form {
        public $fields = [];

        public function as_array($show_all=false) {
            $object_array = [];
            foreach ($this->fields as $field) {
                if ($show_all || $field->editable) {
                    $object_array[] = $field;
                }
            }

            return $object_array;
        }

    }


    class ObjectForm extends Form {

        private function get_input_type($field_type) {
            $field2input = [
                'TextField' => 'textarea',
                'StringField' => 'text',
                'NumberField' => 'number',
                'AutoIncrementField' => 'number',
                'DateTimeField' => 'text',
                'BoolField' => 'checkbox',
                'ForeignKey' => 'text' // @todo: must be select
            ];
            return $field2input[$field_type];
        }

        public function __construct($model) {
            $model_fields = $model->get_fields();
            foreach($model_fields as $mfield_name => $mfield_value) {
                if (!$mfield_value->readonly) {
                    $this->fields[] = new FormField([
                        'name' => $mfield_name,
                        'label' => $mfield_value->name ? $mfield_value->name : ucfirst($mfield_name),
                        'value' => $mfield_value->value ? $mfield_value->value->to_json_format() : null,
                        'type' => $this->get_input_type($mfield_value->type),
                        'editable' => $mfield_value->editable
                    ]);
                }
            }
        }
    }

?>