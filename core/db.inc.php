<?
    include_once('../config.inc.php');


    class Query {
        private $table;
        private $query_text;
        private $data;

        function __construct($type) {
            $this->table = strtolower($type) .'_'. Config::DB_POSTFIX;
        }

        private function execute() {
            echo 'SQL: '. $this->query_text .'<br>';
            try {
                $dbh = new PDO('mysql:host='. Config::DB_HOST .';dbname='. Config::DB_NAME, Config::DB_USER, Config::DB_PASS, array(PDO::ATTR_PERSISTENT => false));
                $stmt = $dbh->prepare($this->query_text);
                $result = $stmt->execute();
                echo 'db exec result: <b>'. $stmt->errorInfo()[2] .'</b><br>';
                if (!$result) return false;

                $this->data = $stmt->fetchAll();
                echo 'db data returned: ';
                var_dump($this->data);
                if (!$this->data) return false;
            } catch (PDOException $e) {
                print "<h2>Error!: " . $e->getMessage() . "</h2>";
//                die();
                return false;
            }
            return true;
        }

        public function get_all() {
            $this->query_text = 'SELECT * FROM '. $this->table;
            if ($this->execute()) return $this->data;
            else return null;
        }

        public function get($id) {
            $this->query_text = 'SELECT * FROM '. $this->table .' WHERE id='. $id;
            if ($this->execute()) return $this->data[0];
            else return null;
        }

        public function save($id, $data) {
            //insert into table (id, name, age) values(1, "A", 19) on duplicate key update name=values(name), age=values(age)
            var_dump($data);
            foreach ($data as $field => $value) {
                $fields[] = $field;
                $values[] = $value->get_db_value();
//                $values[] = $value->value;
                $fields_values[] = $field .'=values('. $field .')';
            }
            $fields = join(', ', $fields);
            $values = join(', ', $values);
            $fields_values = join(', ', $fields_values);
            $this->query_text = 'INSERT INTO '. $this->table .' ('. $fields .') VALUES('. $values .') ON DUPLICATE KEY UPDATE '. $fields_values;
            return $this->execute();
        }

    }

?>