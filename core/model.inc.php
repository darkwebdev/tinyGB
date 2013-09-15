<?
    include_once('helpers.inc.php');
    include_once('db.inc.php');
    include_once('field.inc.php');

    abstract class Model {
        public $id;
        public $name;
        public $created;
        public $is_active;

        function __construct($vars=[]) {
            $this->id = new NumberField();
            $this->id->editable = false;

            $this->name = new StringField();

            $this->created = new DateTimeField();
            $this->created->editable = false;

            $this->is_active = new BoolField();
            $this->is_active->editable = false;
        }

        public function apply_data($data) {
//            echo 'apply: ';
//            var_dump($this);
//            var_dump($data);
//            var_dump(xdebug_get_function_stack());
            foreach ($data as $field => $value) {
//                echo 'field: '. $field;
                if(isset($this->{$field})) {
                    $this->{$field}->value = $value;
                }
//                var_dump($this->{$field});
            }
        }

        private static function create_object($data) {
            $class_name = get_called_class();
            //echo 'creating object of '. $class_name;
            return new $class_name($data);
        }

        public function __get($prop) {
            $value = null;
            if (property_exists($this, $prop)) {
                $value = $this->$prop;
            }
            return $value;
        }

        static function get_all() {
            $query = new Query(get_called_class());
            $data = $query->get_all();

            if ($data) {
                $objects = [];
                foreach ($data as $object_data) {
                    $objects[] = self::create_object($object_data);
                }
                return $objects;
            } else {
                return null;
            }
        }

        static function get($id) {
            $query = new Query(get_called_class());
            $data = $query->get($id);
            echo 'get: ';
            var_dump($data);

            return self::create_object($data);
        }

        public function save() {
            $query = new Query(get_called_class());
            if ($query->save($this->id, get_object_vars($this))) {
                echo 'db returned success<br>';
                return true;
            } else {
                echo 'db returned failure<br>';
                return false;
            }
        }

    }

    class User extends Model {
        public $pass;
        public $is_admin;

        public function __construct($data=[]) {
            echo 'construct data: ';
            var_dump($data);
            parent::__construct();
            $this->name->editable = false;
            $this->pass = new StringField('Password'); // @todo: change to password_field type
            $this->is_admin = new BoolField('Administrator rights');
            $this->is_admin->editable = false;

            $this->apply_data($data);
//            var_dump($this);
        }
    }


    class Entry extends Model {
        public $text;

        public function __construct($data=[]) {
            parent::__construct();
            $this->text = new TextField('Content');

            $this->apply_data($data);
        }
    }


?>