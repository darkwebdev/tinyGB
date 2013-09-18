<?
    include_once('core/response.inc.php');
    include_once('mymodels.inc.php');
    include_once('myforms.inc.php');

    class UserResponse extends Response {

        public function common_context() {
            parent::common_context();

            if ($this->request->is_user_admin()) {
    //                $user_list = User::get_all();
    //                $this->context['user_list'] = $user_list;
            }

        }

        public function create($user_name, $pass, $pass_confirm) {
            //ChromePhp::log('<- user create', $user_name, $pass, $pass_confirm);
            $this->context = array(
                'result' => false,
                'title' => 'Register'
            );

            if ($this->request->method != 'POST') {
                $this->context['result'] = true;

                return $this;
            }

            // @todo: move all validation to the form class
            if (!$user_name || !$pass || !$pass_confirm) {
                $this->context['msg'] = 'There are errors in the form';
                $this->context['user_name'] = $user_name;

                return $this;
            }
            $user_exists = User::get_by('name', $user_name);
            if ($user_exists) {
                //ChromePhp::log('<- user exists', $user_exists);
                $this->context['msg'] = 'User '. $user_name .' already exists';

                return $this;
            }
            if ($pass != $pass_confirm) {
                $this->context['msg'] = 'Passwords are different';

                return $this;
            }

            $user = new User(array(
                'name' => $user_name,
                'pass' => $pass
            ));
            //ChromePhp::log('<- user new', $user);
            if ($user->save()) {
                $this->request->user = $user;
                $this->set_user($user->id);
                $this->context['result'] = true;
                $this->context['redirect'] = '/';
                $this->context['msg'] = 'User '. $user->name .' created';
            }

            return $this;

        }

        public function login($user_name, $pass) {
            //ChromePhp::log('<- user login', $user_name, $pass, $this->request->post);

            $this->context = array(
                'result' => false,
                'title' => 'Login'
            );

            if ($this->request->method != 'POST') {
                $this->context['result'] = true;

                return $this;
            }

            if (!$user_name || !$pass) {
                $this->context['msg'] = 'There are errors in the form';
                $this->context['user_name'] = $user_name;

                return $this;
            }

            $user = User::auth($user_name, $pass);
            if ($user) {
                //ChromePhp::log('<- user auth ok', $user);
                $this->request->user = $user;
                $this->set_user($user->id);
                $this->context['result'] = true;
                $this->context['redirect'] = '/';
            }

            return $this;
        }

        public function logout() {
            $this->set_user(null);
            $this->context = array(
                'result' => true,
                'redirect' => '/'
            );

            return $this;
        }

/*        public function show_all() {
            if ($this->request->is_user_admin()) {
                $user_list = User::get_all();

                $this->context = array(
                    'result' => true,
                    'title' => 'Users',
                    'user_list' => $user_list
                );
            }

            return $this;
      }*/

    }


?>