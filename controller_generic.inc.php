<?
    include_once('core/controller.inc.php');
    include_once('mymodels.inc.php');
    include_once('myforms.inc.php');

    class GenericController extends Controller {

        public function home() {
            // generic empty controller for ajax loader

            return $this->response();
        }

        public function object_edit($class_name, $id=null, $add_context=array()) {
            ChromePhp::log('<- object edit', $class_name, $id);

            if (!$this->request->user) {
                return $this->response_http401();
            }

            $context = array(
                'result' => false,
                'title' => $class_name .' '. ($id ? 'editing' : 'creating')
            );

            if ($id) {
                $object = $class_name::get($id);
                if (!$object || !$this->request->is_user_admin) {
                    $this->response_http404();

                    return $this->response($context);
                }
            } else {
                $object = new $class_name();
            }

            $form = new ObjectForm($object);

            if ($this->request->method == 'POST') {
                ChromePhp::log('<- got post', $this->request->post);

                $errors = $form->validate($this->request->post);
                if (count($errors)) {
                    $context['msg'] = 'There are errors in the form';
                    $context['errors'] = $errors;

                    return $this->response($context);
                }

                $object->apply_data($this->request->post, $add_context);
                $object->save();
                $context['result'] = true;
                $context['redirect'] = '#';

            } else {

                $context['result'] = true;
                $context['form'] = $form;
                $context['url'] = $this->request->server->get('REQUEST_URI');
            }

            return $this->response($context);
        }

        public function object_delete($class_name, $id) {
            ChromePhp::log('<- controller-delete: '. $id);
            ChromePhp::log('<- user', $this->request->is_user_admin(), $this->request->query->get('id'));
            if (!$this->request->is_user_admin() || !$id) {
                return $this->response_http401();
            }

            $object = $class_name::get($id);
            if (!$object) {
                return $this->response_http404();
            }

            $object->is_active = false;
            if (!$object->save()) {
                return $this->response(array(
                    'result' => false,
                    'msg' => 'Could not delete '. $class_name
                ));
            }

            return $this->response(array(
                'result' => true,
                'msg' => $class_name .' deleted',
                'redirect' => '#'
            ));
        }

    }


?>