<?
    include_once('controller.inc.php');


    class Router {
        function __construct() {
            $this->execute();
        }

        private function execute() {
            echo 'router->execute();';
            $request = new Request();
            $action = $request->query->get('action');
            $response = new MyResponse($request);

            switch ($action) {
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

                case 'user_edit':
                    $id = $request->query->get('id');
                    $response->object_edit('User', $id);
                    break;
                default:
                    $response->http404();
            }

            $response->render();
        }
    }

?>