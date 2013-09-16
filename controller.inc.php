<?
    include_once('core/request.inc.php');
    include_once('core/response.inc.php');
    include_once('core/form.inc.php');

    class MyResponse extends Response {

        public function common_context() {
            parent::common_context();

            if ($this->request->is_user_admin()) {
                $user_list = User::get_all();
                $this->context['user_list'] = $user_list;
            }

        }

        public function user_create($user_name, $pass, $pass_confirm) {
//            if ($this->request->is_user_admin()) {
                if ($this->request->method == 'POST') {
                    if ($pass == $pass_confirm) {
                        $user = new User([
                            'name' => $user_name,
                            'pass' => $pass
                        ]);
                        if ($user->save()) {
                            $this->request->user = $user;
                            $this->set_user($user->id);
                            $this->context = [
                                'result' => true,
                                'msg' => 'User '. $user->name .'created'
                            ];
                        }
                    } else {
                        $this->context = [
                            'result' => false,
                            'msg' => 'Passwords are different'
                        ];
                    }
                }
                $this->context = [
                    'title' => 'Login',
                    'user_name' => $user_name
                ];
//            } else {
//                $this->http401();
//            }
        }
        public function user_login($user_name, $pass) {
            if ($this->request->method == 'POST') {
                $user = User::auth($user_name, $pass);
                if ($user) {
                    $this->request->user = $user;
                    $this->set_user($user->id);
                    return;
                }
            }
            $this->context = [
                'title' => 'Login',
                'user_name' => $user_name
            ];
        }

        public function user_logout() {
            $this->set_user(null);
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
//                echo 'post:';
//                var_dump($this->request->post);
                $object->apply_data($this->request->post);
                $object->save();
            }

            $form = new Form($object);

            $this->context = [
                'title' => $class_name .' '. $id ? 'editing' : 'creating',
                'form' => $form,
                'template' => 'edit'
            ];
        }

        public function object_delete($class_name, $id) {
            ChromePhp::log('controller->delete: '. $id);
            if ($id) {
                $object = $class_name::get($id);
                if (!$object) {
                    $this->http404();
                    return;
                } else {
                    $object->is_active = false;
                    if ($object->save()) {
                        $this->context = [
                            'result' => true,
                            'msg' => $class_name .' deleted'
                        ];
                    } else {
                        $this->context = [
                            'result' => false,
                            'msg' => 'Could not delete '. $class_name
                        ];
                    }
                }
            }
        }

        public function entry_list() {
            if ($this->request->is_user_admin()) {
                $entry_list = Entry::get_all();
            } else {
                $entry_list = Entry::get_active();
            }

            $this->context = [
                'title' => 'Entry listing',
                'entry_list' => $entry_list,
                'template' => 'entry_list'
            ];
        }

        public function user_list() {
            if ($this->request->is_user_admin()) {
                $user_list = User::get_all();

                $this->context = [
                    'title' => 'User listing',
                    'user_list' => $user_list,
                    'template' => 'user_list'
                ];
            }
        }

    }


?>