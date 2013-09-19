<?
    include_once('view.inc.php');

    class Response {
        protected $context = array();
        protected $is_ajax;
        protected $default_template = 'home';

        public function __construct($is_ajax=false) {
            //ChromePhp::log('<- new response', $is_ajax);
            $this->is_ajax = $is_ajax;
        }

        public function expand_context($add_context) {
            $this->context = array_merge($this->context, $add_context);
        }
        public function send() {
            session_write_close();

            if ($this->is_ajax) {
                //ChromePhp::log('<- render AJAX', $this->context);
                print $this->get_json();
            } else {
                //ChromePhp::log('<- render HTML', $this->context);
                print $this->get_html();
            }
        }

        protected function get_html() {
            $template_prefix = array_key_exists('template', $this->context) ? $this->context['template'] : $this->default_template;
            unset($this->context['template']);
            $template = new Template($this->context);
            $template->prefix = $template_prefix;
            return $template->get_html();
        }

        protected function get_json() {
            $show_all = $this->context['user'] ? $this->context['user']->is_admin : false;
            return json_serialize($this->context, $show_all);
        }
    }
?>