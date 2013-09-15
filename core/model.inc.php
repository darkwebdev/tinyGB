<?
    include_once('helpers.inc.php');
    include_once('db.inc.php');
    include_once('field.inc.php');

    abstract class Model {
        protected $id;
        protected $name;
        protected $created;
        protected $is_active;

        function __construct($vars=[]) {
            $this->id = new AutoIncrementField();

            $this->name = new StringField();

            $this->created = new DateTimeField();
            $this->created->editable = false;

            $this->is_active = new BoolField();
            $this->is_active->editable = false;
        }

        public function apply_data($data) {
//            var_dump(xdebug_get_function_stack());
            foreach ($data as $field => $value) {
//                echo 'field: '. $field;
                if(isset($this->{$field})) {
                    $this->{$field}->value = $value;
                }
//                var_dump($this->{$field});
            }
//            var_dump($this);
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

        public function __set($property, $value) {
//            var_dump($property);
            if (property_exists($this, $property)) {
                $this->$property->value = $value;
            }

            // @todo: add additional checks
            return $this;
        }

        static function get_all($filter=[]) {
            $query = new Query(get_called_class());
            $data = $query->get_all($filter);

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
//            echo 'get: ';
//            var_dump($data);

            return self::create_object($data);
        }

        public function save() {
            Analog::log('model->save');
            $query = new Query(get_called_class());
            if ($query->save($this->id, get_object_vars($this))) {
                Analog::log('db returned success');
                return true;
            } else {
                Analog::log('db returned failure');
                return false;
            }
        }

        public function as_array() {
            $object_array = [];
            foreach (get_object_vars($this) as $field => $value) {
                $object_array[$field] = $value->value;
            }
            return $object_array;
        }

    }

    class User extends Model {
        protected $pass;
        protected $is_admin;

        public function __construct($data=[]) {
//            echo 'construct data: ';
//            var_dump($data);
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
        protected $text;
        protected $author;

        public function __construct($data=[]) {
            parent::__construct();

            $this->text = new TextField('Content');

            $this->author = new ForeignKey();
            $this->author->model = 'User';

            $this->apply_data($data);
        }

        static public function get_active() {
            return self::get_all(['is_active' => true]);
        }

    }

?>