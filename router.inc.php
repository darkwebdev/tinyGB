<?
    include_once('controller.inc.php');


    class Router {
        function __construct() {
            $this->execute();
        }

        private function execute() {
//            echo 'router->execute();';
            $request = new Request();
            $action = $request->query->get('action');
            $response = new MyResponse($request);

            switch ($action) {
                case null:
                    $response->home();
                    break;

                case 'entries':
                    $response->entry_list();
                    break;

                case 'entry_new':
                    $response->object_edit('Entry');
                    break;
                case 'entry_edit':
                    $id = $request->query->get('id');
                    $response->object_edit('Entry', $id);
                    break;
                case 'entry_delete':
                    $id = $request->query->get('id');
                    $response->object_delete('Entry', $id);
                    break;

                case 'user_new':
                    $user_name = $request->post->get('user_name');
                    $pass = $request->post->get('pass');
                    $pass_confirm = $request->post->get('pass_confirm');
                    $response->user_create($user_name, $pass, $pass_confirm);
                    break;
                case 'user_edit':
                    $id = $request->query->get('id');
                    $response->object_edit('User', $id);
                    break;
                case 'user_login':
                    $user_name = $request->post->get('user_name');
                    $pass = $request->post->get('pass');
                    $response->user_login($user_name, $pass);
                    break;
                case 'user_logout':
                    $response->user_logout();
                    break;

                default:
                    $response->http404();
            }

            $response->render();
        }
    }

?>