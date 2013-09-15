<?
    include_once('core/request.inc.php');
    include_once('core/response.inc.php');
    include_once('core/form.inc.php');

    class MyResponse extends Response {

        public function common_context() {
            parent::common_context();

            if ($this->context['user']->is_admin) {
                $user_list = User::get_all();
                $this->context['user_list'] = $user_list;
            }

        }

        public function object_edit($class_name, $id=null) {

            if ($id) {
                $object = $class_name::get($id);
                if (!$object) {
                    $this->http404();
                    return;
                }
            } else {
                $object = new $class_name();
            }

            if ($this->request->method == 'POST') {
                echo 'post:';
                var_dump($this->request->post);
                $object->apply_data($this->request->post);
                $object->save();
            }

            $form = new Form($object);

            $this->context = [
                'html_title' => $class_name .' '. $id ? 'editing' : 'creating',
                'form' => $form,
                'template' => 'edit'
            ];
        }

        public function entry_list() {
            $entry_list = Entry::get_all();

            $this->context = [
                'html_title' => 'Entry listing',
                'entry_list' => $entry_list,
                'template' => 'entry_list'
            ];
        }

        public function user_list() {
            $user = $this->request->user;
            if ($user->is_admin) {
                $user_list = User::get_all();

                $this->context = [
                    'html_title' => 'User listing',
                    'user_list' => $user_list,
                    'template' => 'user_list'
                ];
            }
        }

    }


?>