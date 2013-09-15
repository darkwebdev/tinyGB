<?
    include_once('model.inc.php');


    class Request {
        public $user;
        public $server;
        public $get;
        public $post;
        public $cookie;
        public $query;
        public $is_ajax;
        public $method;

        function __construct() {
            $this->server = new Dict($_SERVER);
            $this->get = new Dict(sanitize($_GET));
            $this->post = new Dict(sanitize($_POST));
            $this->cookie = new Dict($_COOKIE);
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
            parse_str($this->server->get('QUERY_STRING'), $array);
            $this->query = new Dict(sanitize($array));

            $this->extract_user();
        }

        public function extract_user() {
            $this->user = new User([
                'name' => 'tibalt',
                'is_admin' => true
            ]); // mocked data
        }

    }

?>