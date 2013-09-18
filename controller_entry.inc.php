<?
    include_once('core/response.inc.php');
    include_once('mymodels.inc.php');
    include_once('myforms.inc.php');

    class EntryResponse extends Response {

        public function common_context() {
            parent::common_context();
        }

        public function approve($id) {
            if (!$this->request->is_user_admin() || !$id) {
                $this->http401();

                return $this;
            }

            $entry = Entry::get($id);
            if (!$entry) {
                $this->http404();

                return $this;
            }

            $entry->is_active = true;
            if (!$entry->save()) {
                $this->context = array(
                    'result' => false,
                    'msg' => 'Could not approve Entry (NSA prohibited)'
                );
            }

            $this->context = array(
                'result' => true,
                'redirect' => '#'
            );

            return $this;
        }

        public function show_all() {
            if ($this->request->is_user_admin()) {
                $entry_list = Entry::get_all();
            } else {
                $entry_list = Entry::get_active();
            }

            $this->context = array(
                'result' => true,
                'title' => 'Messages',
                'entry_list' => $entry_list
            );

            return $this;
        }

    }


?>