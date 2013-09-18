<?
    include_once('model.inc.php');
    include_once('../lib/PasswordHash.php');


    class User extends Model {
        protected $pass;
        protected $is_admin;
        protected $last_login;

        public function __construct($data=array()) {
            parent::__construct();
            //ChromePhp::log('<- user construct', $data);

            $this->name->editable = false;

            $this->pass = new PasswordField('Password');

            $this->is_admin = new BoolField('Administrator rights');
            $this->is_admin->editable = false;

            $this->last_login = new DateTimeField();
            $this->last_login->editable = false;

            $this->apply_data($data);
        }

        protected function on_apply_data($data, $add_context) {
        }

        public static function auth($user_name, $pass) {
            $user = self::get_by('name', $user_name);
    //        ChromePhp::log('auth check', (string)$user->pass, $user_name, $pass);
            if ($user && Auth::check($pass, (string)$user->pass)) {
    //            ChromePhp::log('auth ok');
                return $user;
            } else {
    //            ChromePhp::log('auth failed');
                return null;
            }
        }
    }

    class Auth {

        private static function get_hasher() {
            return new PasswordHash(8, FALSE);
        }

        public static function get_hash($pass) {
            $hasher = self::get_hasher();
            $hash = $hasher->HashPassword($pass);
            return $hash;
        }

        public static function check($pass, $hash) {
            $hasher = self::get_hasher();
            $check = $hasher->CheckPassword($pass, $hash);
            return $check;
        }

    }


?>