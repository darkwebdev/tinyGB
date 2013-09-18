<?
    include_once('core/response.inc.php');
    include_once('mymodels.inc.php');
    include_once('myforms.inc.php');

    class MyResponse extends Response {

        public function common_context() {
            parent::common_context();

            if ($this->request->is_user_admin()) {
//                $user_list = User::get_all();
//                $this->context['user_list'] = $user_list;
            }

        }

        public function user_create($user_name, $pass, $pass_confirm) {
            ChromePhp::log('user create', $user_name, $pass, $pass_confirm);
            $this->context = array(
                'result' => false,
                'title' => 'Register'
            );

            if ($this->request->method != 'POST') {
                $this->context['result'] = true;
                return;
            }

            if (!$user_name || !$pass || !$pass_confirm) {
                $this->context['msg'] = 'There are errors in the form';
                $this->context['user_name'] = $user_name;
                return;
            }

            if ($pass == $pass_confirm) {
                $user = new User([
                    'name' => $user_name,
                    'pass' => $pass
                ]);
                ChromePhp::log($user);
                if ($user->save()) {
                    $this->request->user = $user;
                    $this->set_user($user->id);
                    $this->context['result'] = true;
                    $this->context['msg'] = 'User '. $user->name .' created';
                }
            } else {
                $this->context['msg'] = 'Passwords are different';
            }

        }

        public function user_login($user_name, $pass) {
            ChromePhp::log('user login', $user_name, $pass, $this->request->post);

            $this->context = array(
                'result' => false,
                'title' => 'Login'
            );

            if ($this->request->method != 'POST') {
                $this->context['result'] = true;
                return;
            }

            if (!$user_name || !$pass) {
                $this->context['msg'] = 'There are errors in the form';
                $this->context['user_name'] = $user_name;
                return;
            }

            $user = User::auth($user_name, $pass);
            if ($user) {
                ChromePhp::log('user', $user);
                $this->request->user = $user;
                $this->set_user($user->id);
                $this->context['result'] = true;
                $this->context['redirect'] = true;
            }
        }

        public function user_logout() {
            $this->set_user(null);
            $this->context = array(
                'redirect' => true
            );
        }

        public function object_edit($class_name, $id=null, $add_context=array()) {
            ChromePhp::log('object edit', $class_name, $id);

            if (!$this->request->user) {
                $this->http401();
                return;
            }

            $this->context = array(
                'result' => false,
                'title' => $class_name .' '. ($id ? 'editing' : 'creating')
            );

            if ($id) {
                $object = $class_name::get($id);
                if (!$object || !$this->request->is_user_admin) {
                    $this->http404();
                    return;
                }
            } else {
                $object = new $class_name();
            }

            $form = new ObjectForm($object);

            if ($this->request->method == 'POST') {
                ChromePhp::log('server got post', $this->request->post);

                $errors = $form->validate($this->request->post);
                if (count($errors)) {
                    $this->context['msg'] = 'There are errors in the form';
                    $this->context['errors'] = $errors;
                    return;
                }

                $object->apply_data($this->request->post, $add_context);
                $object->save();
                $this->context['result'] = true;
                $this->context['redirect'] = true;

            } else {

                $this->context['result'] = true;
                $this->context['form'] = $form;
                $this->context['url'] = $this->request->server->get('REQUEST_URI');
            }
        }

        public function object_delete($class_name, $id) {
            ChromePhp::log('controller->delete: '. $id);
            ChromePhp::log('server user', $this->request->is_user_admin(), $this->request->query->get('id'));
            if (!$this->request->is_user_admin() || !$id) {
                $this->http401();
                return;
            }

            $object = $class_name::get($id);
            if (!$object) {
                $this->http404();
                return;
            }

            $object->is_active = false;
            if (!$object->save()) {
                $this->context = array(
                    'result' => false,
                    'msg' => 'Could not delete '. $class_name
                );
            }

            $this->context = array(
                'result' => true,
                'msg' => $class_name .' deleted',
                'redirect' => true
            );

        }

        public function entry_approve($id) {
            if (!$this->request->is_user_admin() || !$id) {
                $this->http401();
                return;
            }

            $entry = Entry::get($id);
            if (!$entry) {
                $this->http404();
                return;
            }

            $entry->is_active = true;
            if (!$entry->save()) {
                $this->context = array(
                    'result' => false,
                    'msg' => 'Could not approve Entry '
                );
            }

            $this->context = array(
                'result' => true,
                'redirect' => true
            );
        }

        public function entry_list() {
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
        }

        public function user_list() {
            if ($this->request->is_user_admin()) {
                $user_list = User::get_all();

                $this->context = array(
                    'title' => 'User listing',
                    'user_list' => $user_list,
                    'template' => 'user_list'
                );
            }
        }

        public function home() {
        }
    }


?>