<?
    include_once('auth.inc.php');


    class Request {
        public $user;
        public $server;
        public $get;
        public $post;
        public $cookie;
        public $query;
        public $is_ajax;
        public $method;
        public $session;
        public $payload;

        function __construct() {
            session_start();

            $this->session = new Dict(sanitize(isset($_SESSION) ? $_SESSION : array()));
            //ChromePhp::log('<- session', $this->session);
            $this->server = new Dict(sanitize($_SERVER));
            $this->get = new Dict(sanitize($_GET));
            //ChromePhp::log('<- get', $_GET);
            $this->post = new Dict(sanitize($_POST));
            //ChromePhp::log('<- post', $_POST);
            $this->request = new Dict(sanitize($_REQUEST));
            $this->cookie = new Dict(sanitize($_COOKIE));

            $this->method = $this->server->get('REQUEST_METHOD');
            $this->is_ajax = $this->server->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';
            parse_str(urldecode($this->server->get('QUERY_STRING')), $array);
            $this->query = new Dict(sanitize($array));
            //ChromePhp::log('<- query', $this->query);
            //ChromePhp::log('<- ', $this->method, $this->is_ajax ? 'AJAX' : '', $this->server->get('QUERY_STRING'), $_POST);
            parse_str(urldecode(file_get_contents('php://input')), $array);
            $this->payload = new Dict(sanitize($array));
            //ChromePhp::log('<- payload', $this->payload);
            if (!$this->post->count()) $this->post = $this->payload;

            $this->detect_user();
        }

        public function get($var) {
            $value = $this->request->get($var);
            if (!$value && $this->method == 'POST') $value = $this->post->get($var); // maybe a bad idea
            return $value;
        }

        public function __get($property) {
            $value = null;
            if (property_exists($this, $property)) {
                $value = $this->$property;
            } else if (method_exists($this, $property)) {
                $value = $this->$property();
            }
            return $value;
        }

        public function detect_user() {
            $user_id = $this->session->get('user');
            if ($user_id) {
                $user = User::get($user_id);
                if ($user) {
                    $this->user = $user;
                }
            }
        }

        public function is_user_admin() {
//            //ChromePhp::log('is admin', $this->user, $this->user->is_admin);
            return $this->user && $this->user->is_admin;
        }

    }

?>