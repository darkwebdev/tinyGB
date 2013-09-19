<?
    include_once('core/controller.inc.php');
    include_once('mymodels.inc.php');
    include_once('myforms.inc.php');

    class EntryController extends Controller {

        public function get_common_context() {
            $context = parent::get_common_context();
            // expand context here

            return $context;
        }

        public function approve($id) {
            if (!$this->request->is_user_admin() || !$id) {
                return $this->response_http401();
            }

            $entry = Entry::get($id);
            if (!$entry) {
                return $this->response_http404();
            }

            $entry->is_active = true;
            if (!$entry->save()) {
                return $this->response(array(
                    'result' => false,
                    'msg' => 'Could not approve Entry (NSA prohibited)'
                ));
            }

            return $this->response(array(
                'result' => true,
                'redirect' => '#'
            ));
        }

        public function show_all() {
            if ($this->request->is_user_admin()) {
                $entry_list = Entry::get_all();
            } else {
                $entry_list = Entry::get_active();
            }

            return $this->response(array(
                'result' => true,
                'title' => 'Messages',
                'entry_list' => $entry_list
            ));
        }

    }


?>