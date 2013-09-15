<?
    class Template {
        private $context;
        public $prefix;
        private $html;

        function __construct($context) {
            $this->context = $context;
        }

        private function render() {
            ob_start();

            extract($this->context);
            include('../templates/header.inc.html.php');
            include('../templates/'. $this->prefix .'.inc.html.php');
            include('../templates/footer.inc.html.php');

            $this->html = ob_get_contents();
            ob_end_clean();
        }

        public function get_html() {
            $this->render();
            return $this->html;
        }
    }
?>