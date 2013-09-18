<?
    include_once('request.inc.php');
    include_once('view.inc.php');

    class Response {
        protected $context = array();
        protected $request;

        function __construct($request) {
            $this->request = $request;
        }

        public function render() {
            session_write_close();

            $this->common_context();

            if ($this->request->is_ajax) {
                ChromePhp::log('server render AJAX', $this->context);
                unset($this->context['template']);
                $show_all = $this->request->is_user_admin();
                print json_serialize($this->context, $show_all);
//                print json_encode($this->context);
            } else {
                ChromePhp::log('response render HTML', $this->context);
                $template = new Template($this->context);
                $template->prefix = array_key_exists('template', $this->context) ? $this->context['template'] : 'home';
                print $template->get_html();
            }
        }

        public function common_context() {
            // expand context here
            $this->context['user'] = $this->request->user;
        }

        public function http404() {
            $this->context = array(
                'title' => 'Error 404',
                'template' => 'http404'
            );
        }
        public function http401() {
            $this->context = array(
                'title' => 'Error 401: Unauthorized',
                'template' => 'http401'
            );
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