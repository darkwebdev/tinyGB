<?
    include_once('core/request.inc.php');
    include_once('controller_user.inc.php');
    include_once('controller_entry.inc.php');
    include_once('controller_generic.inc.php');


    class Router {

        public function get_response() {
            $request = new Request();
            $action = $request->get('action');

            $entry_controller = new EntryController($request);
            $user_controller = new UserController($request);
            $controller = new GenericController($request);

            switch ($action) {
                case null:
                    return $controller->home();


                case 'entries':
                    return $entry_controller->show_all();

                case 'entry_new':
                    $context = array('author' => $request->user);
                    return $controller->object_edit('Entry', null, $context);

                case 'entry_edit':
                    $id = $request->get('id');
                    return $controller->object_edit('Entry', $id);

                case 'entry_approve':
                    $id = $request->get('id');
                    return $entry_controller->approve($id);

                case 'entry_delete':
                    $id = $request->get('id');
                    return $controller->object_delete('Entry', $id);


                case 'user_new':
                    $user_name = $request->get('user_name');
                    $pass = $request->get('pass');
                    $pass_confirm = $request->get('pass_confirm');

                    return $user_controller->create($user_name, $pass, $pass_confirm);
                case 'user_edit':
                    $id = $request->get('id');
                    return $controller->object_edit('User', $id);

                case 'user_login':
                    $user_name = $request->get('user_name');
                    $pass = $request->get('pass');
                    return $user_controller->login($user_name, $pass);

                case 'user_logout':
                    return $user_controller->logout();


                default:
                    return $controller->http404();
            }

        }
    }

?>