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

        function __construct() {
            $this->session = new Dict(sanitize(isset($_SESSION) ? $_SESSION : []));
            ChromePhp::log('session', $this->session);
            $this->server = new Dict(sanitize($_SERVER));
            ChromePhp::log('server', $_SERVER);
            $this->get = new Dict(sanitize($_GET));
            ChromePhp::log('get', $_GET);
            $this->post = new Dict(sanitize($_POST));
            ChromePhp::log('post', $_POST);
            $this->cookie = new Dict(sanitize($_COOKIE));

            $this->method = $this->server->get('REQUEST_METHOD');
            $this->is_ajax = $this->server->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';
            parse_str($this->server->get('QUERY_STRING'), $array);
            $this->query = new Dict(sanitize($array));
            ChromePhp::log($this->method, $this->is_ajax ? 'AJAX' : '', $this->server->get('QUERY_STRING'), $_POST);

            session_start();

            $this->detect_user();
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
            return $this->user && $this->user->is_admin;
        }

    }

?>