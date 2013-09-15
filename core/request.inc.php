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
            $this->get = new Dict($_GET);
            $this->post = new Dict($_POST); // @todo: sanitate user input
            $this->cookie = new Dict($_COOKIE);
            $this->method = $_SERVER['REQUEST_METHOD'];
            //print_r($this->server);
            $this->query = new Dict($this->server->get('QUERY_STRING'));
            //urldecode()

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