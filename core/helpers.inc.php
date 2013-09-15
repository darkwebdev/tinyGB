<?

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