<?

    function json_serialize($data) {
        return str_replace('\\u0000*\\u0000', '', json_encode(objectToArray($data)));
    }
    function objectToArray($object) {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }
        if (is_object($object)) {
            $object = $object->as_array();
        }
        return array_map('objectToArray', $object);
    }

    function sanitize($data) {
//        var_dump($data);
        if (is_array($data)) {
            $result = [];
            foreach($data as $field => $value) {
                $result[sanitize_string($field)] = sanitize_string($value);
            }
            return $result;
        } else {
            return sanitize_string($data);
        }
    }
    function sanitize_string($str) {
        return trim(htmlentities($str));
    }

    class Dict implements IteratorAggregate {
        private $vars;

        function __construct($vars) {
            if (gettype($vars) == 'array') {
                $this->vars = $vars;
            } else {
                $this->vars = $this->parse($vars);
            }
        }

        public function getIterator() {
            return new ArrayIterator($this->vars);
        }

        public function get($key) {
            $value = null;
            if (array_key_exists($key, $this->vars)) {
                $value = $this->vars[$key];
            }
            return $value;
        }

        private function parse($string) {
            parse_str($string, $dict);
            return $dict;
        }
    }

//            var_dump($this);
//            var_dump(xdebug_get_declared_vars());
//            var_dump(xdebug_get_function_stack());

?>