<?
    include_once('response.inc.php');

    abstract class Controller {
        protected $request;
        protected $response;

        function __construct($request) {
            //ChromePhp::log('<- new controller', $request);
            $this->request = $request;
            $this->response = new Response($is_ajax=$request->is_ajax);
        }

        public function get_common_context() {
            $context = array();
            $context['user'] = $this->request->user;
            $context['debug'] = $this->request->get('debug') ? true : false;

            return $context;
        }

        protected function response($context=array()) {
            $this->response->expand_context($context);
            $this->response->expand_context($this->get_common_context());

            return $this->response;
        }

        public function response_http404() {
            return $this->response(array(
                'title' => 'Error 404',
                'template' => 'http404'
            ));
        }
        public function response_http401() {
            return $this->response(array(
                'title' => 'Error 401: Unauthorized',
                'template' => 'http401'
            ));
        }

        public function set_user($user_id) {
            if ($user_id) {
                session_regenerate_id(true);
                $_SESSION['user'] = $user_id;
            } else {
                session_destroy();
                unset($_SESSION['user']);
            }
        }
    }

?>