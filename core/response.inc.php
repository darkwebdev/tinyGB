<?
    include_once('view.inc.php');

    class Response {
        protected $context = [];
        protected $request;

        function __construct($request) {
            $this->request = $request;
        }

        public function render() {
            $this->common_context();
            $template = new Template($this->context);
            $template->prefix = $this->context['template'];
            print $template->get_html();
        }

        public function common_context() {
            // expand context here
            $this->context['user'] = $this->request->user;
        }

        public function http404() {
            $this->context = [
                'html_title' => 'Error 404',
                'template' => 'http404'
            ];
        }
    }
?>