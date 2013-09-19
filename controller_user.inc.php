<?
    include_once('core/controller.inc.php');
    include_once('mymodels.inc.php');
    include_once('myforms.inc.php');

    class UserController extends Controller {

        public function get_common_context() {
            $context = parent::get_common_context();

            if ($this->request->is_user_admin()) {
//                $user_list = User::get_all();
//                $context['user_list'] = $user_list;
            }

            return $context;
        }

        public function create($user_name, $pass, $pass_confirm) {
            //ChromePhp::log('<- user create', $user_name, $pass, $pass_confirm);
            $context = array(
                'result' => false,
                'title' => 'Register'
            );

            if ($this->request->method != 'POST') {
                $context['result'] = true;

                return $this->response($context);
            }

            // @todo: move all validation to the form class
            if (!$user_name || !$pass || !$pass_confirm) {
                $context['msg'] = 'There are errors in the form';
                $context['user_name'] = $user_name;

                return $this->response($context);
            }
            $user_exists = User::get_by('name', $user_name);
            if ($user_exists) {
                //ChromePhp::log('<- user exists', $user_exists);
                $context['msg'] = 'User '. $user_name .' already exists';

                return $this->response($context);
            }
            if ($pass != $pass_confirm) {
                $context['msg'] = 'Passwords are different';

                return $this->response($context);
            }

            $user = new User(array(
                'name' => $user_name,
                'pass' => $pass
            ));
            //ChromePhp::log('<- user new', $user);
            if ($user->save()) {
                $this->request->user = $user;
                $this->set_user($user->id);
                $context['result'] = true;
                $context['redirect'] = '/';
                $context['msg'] = 'User '. $user->name .' created';
            }

            return $this->response($context);
        }

        public function login($user_name, $pass) {
            //ChromePhp::log('<- user login', $user_name, $pass, $this->request->post);

            $context = array(
                'result' => false,
                'title' => 'Login'
            );

            if ($this->request->method != 'POST') {
                $context['result'] = true;

                return $this->response($context);
            }

            if (!$user_name || !$pass) {
                $context['msg'] = 'There are errors in the form';
                $context['user_name'] = $user_name;

                return $this->response($context);
            }

            $user = User::auth($user_name, $pass);
            if ($user) {
                //ChromePhp::log('<- user auth ok', $user);
                $this->request->user = $user;
                $this->set_user($user->id);
                $context['result'] = true;
                $context['redirect'] = '/';
            }

            return $this->response($context);
        }

        public function logout() {
            $this->set_user(null);

            return $this->response(array(
                'result' => true,
                'redirect' => '/'
            ));
        }

/*        public function show_all() {
            if ($this->request->is_user_admin()) {
                $user_list = User::get_all();

                $context = array(
                    'result' => true,
                    'title' => 'Users',
                    'user_list' => $user_list
                );
            }

            return $this->response($context);
      }*/

    }


?>