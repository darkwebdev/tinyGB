<?
    include_once('../config.inc.php');


    class Query {
        private $table;
        private $query_text;
        private $data;
        private $last_id;

        function __construct($type) {
            $this->table = strtolower($type) .'_'. Config::DB_POSTFIX;
        }

        private function execute() {
            ChromePhp::log('SQL: '. $this->query_text);
            try {
                $dbh = new PDO('mysql:host='. Config::DB_HOST .';dbname='. Config::DB_NAME, Config::DB_USER, Config::DB_PASS, array(PDO::ATTR_PERSISTENT => false));
                $stmt = $dbh->prepare($this->query_text);
                $result = $stmt->execute();
//                ChromePhp::log('db exec result: '. $stmt->errorInfo()[2]);
                if (!$result) return false;

                $this->last_id = $dbh->lastInsertId('id');
                $this->data = $stmt->fetchAll();
                ChromePhp::log('DB DATA:', $this->data);
//                var_dump($this->data);
                if ($this->data === null) return false;
            } catch (PDOException $e) {
                ChromePhp::log("Error!: " . $e->getMessage());
                return false;
            }
//            ChromePhp::log('db returned success');
            return true;
        }

        public function get_all($filter_data) {
            $filter_query = '';
            if (count($filter_data)) {
                $filter_query = ' WHERE ';
                $filter_list = [];
                foreach($filter_data as $field => $value) {
                    $filter_list[] = $field .'='. $value;
                }
                $filter_query .= join(', ', $filter_list);
            }
            $this->query_text = 'SELECT * FROM '. $this->table . $filter_query;

            if ($this->execute()) return $this->data;
            else return null;
        }

        public function get($id) {
            $this->query_text = 'SELECT * FROM '. $this->table .' WHERE id='. $id;
            if ($this->execute() && $this->data) return $this->data[0];
            else return null;
        }
        public function get_by($field, $value) {
            $this->query_text = 'SELECT * FROM '. $this->table .' WHERE '. $field .'="'. $value .'"';
            if ($this->execute() && $this->data) return $this->data[0];
            else return null;
        }

        public function save($id, $data) {
            ChromePhp::log('db->save');
            //insert into table (id, name, age) values(1, "A", 19) on duplicate key update name=values(name), age=values(age)
            foreach ($data as $field => $value) {
//                var_dump($value);
                $fields[] = $field;
                $values[] = $value->to_db_format();
//                $values[] = $value->value;
                $fields_values[] = $field .'=values('. $field .')';
            }
            $fields = join(', ', $fields);
            $values = join(', ', $values);
            $fields_values = join(', ', $fields_values);
            $this->query_text = 'INSERT INTO '. $this->table .' ('. $fields .') VALUES('. $values .') ON DUPLICATE KEY UPDATE '. $fields_values;
            $this->execute();
            return $this->last_id;
        }

    }

?>