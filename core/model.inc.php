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
            foreach ($data as $field => $value) {
                if(isset($this->{$field})) {
                    $this->{$field}->value = $value;
                }
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
                $value = $this->$prop->value;
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

            if ($data) {
                return self::create_object($data);
            } else {
                return null;
            }
        }

        static function get_by($field, $value) {
            $query = new Query(get_called_class());
            $data = $query->get_by($field, $value);
//            echo 'get: ';
//            var_dump($data);

            if ($data) {
                return self::create_object($data);
            } else {
                return null;
            }
        }

        public function save() {
            ChromePhp::log('model->save');
            $query = new Query(get_called_class());
            $id = $query->save($this->id, get_object_vars($this));
            if ($id) $this->id = $id;
            ChromePhp::log('DB last id', $id);
            return $id;
        }

        public function as_array() {
            $object_array = [];
            foreach (get_object_vars($this) as $field => $value) {
                $object_array[$field] = $value->to_json_format();
            }
            ChromePhp::log('asarray', $object_array);
            return $object_array;
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
            $this->author->editable = false;

            $this->apply_data($data);
        }

        static public function get_active() {
            return self::get_all(['is_active' => true]);
        }

    }

?>