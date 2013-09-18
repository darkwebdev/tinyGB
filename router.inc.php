<?
    include_once('controller_user.inc.php');
    include_once('controller_entry.inc.php');
    include_once('controller.inc.php');


    class Router {
        function __construct() {
            $response = $this->get_response();
            $response->render();
        }

        private function get_response() {
            $request = new Request();
            $action = $request->get('action');

            $entry_response = new EntryResponse($request);
            $user_response = new UserResponse($request);
            $response = new GenericResponse($request);

            switch ($action) {
                case null:
                    return $response->home();


                case 'entries':
                    return $entry_response->show_all();

                case 'entry_new':
                    $context = array('author' => $request->user);
                    return $response->object_edit('Entry', null, $context);

                case 'entry_edit':
                    $id = $request->get('id');
                    return $response->object_edit('Entry', $id);

                case 'entry_approve':
                    $id = $request->get('id');
                    return $entry_response->approve($id);

                case 'entry_delete':
                    $id = $request->get('id');
                    return $response->object_delete('Entry', $id);


                case 'user_new':
                    $user_name = $request->get('user_name');
                    $pass = $request->get('pass');
                    $pass_confirm = $request->get('pass_confirm');

                    return $user_response->create($user_name, $pass, $pass_confirm);
                case 'user_edit':
                    $id = $request->get('id');
                    return $response->object_edit('User', $id);

                case 'user_login':
                    $user_name = $request->get('user_name');
                    $pass = $request->get('pass');
                    return $user_response->login($user_name, $pass);

                case 'user_logout':
                    return $user_response->logout();


                default:
                    return $response->http404();
            }

        }
    }

?>