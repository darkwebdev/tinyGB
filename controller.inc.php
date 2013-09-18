<?
    include_once('core/response.inc.php');
    include_once('mymodels.inc.php');
    include_once('myforms.inc.php');

    class GenericResponse extends Response {

        public function common_context() {
            parent::common_context();
        }

        public function home() {
            // generic empty controller for ajax loader

            return $this;
        }

        public function object_edit($class_name, $id=null, $add_context=array()) {
            //ChromePhp::log('<- object edit', $class_name, $id);

            if (!$this->request->user) {
                $this->http401();

                return $this;
            }

            $this->context = array(
                'result' => false,
                'title' => $class_name .' '. ($id ? 'editing' : 'creating')
            );

            if ($id) {
                $object = $class_name::get($id);
                if (!$object || !$this->request->is_user_admin) {
                    $this->http404();

                    return $this;
                }
            } else {
                $object = new $class_name();
            }

            $form = new ObjectForm($object);

            if ($this->request->method == 'POST') {
                //ChromePhp::log('<- got post', $this->request->post);

                $errors = $form->validate($this->request->post);
                if (count($errors)) {
                    $this->context['msg'] = 'There are errors in the form';
                    $this->context['errors'] = $errors;

                    return $this;
                }

                $object->apply_data($this->request->post, $add_context);
                $object->save();
                $this->context['result'] = true;
                $this->context['redirect'] = '#';

            } else {

                $this->context['result'] = true;
                $this->context['form'] = $form;
                $this->context['url'] = $this->request->server->get('REQUEST_URI');
            }

            return $this;
        }

        public function object_delete($class_name, $id) {
            //ChromePhp::log('<- controller-delete: '. $id);
            //ChromePhp::log('<- user', $this->request->is_user_admin(), $this->request->query->get('id'));
            if (!$this->request->is_user_admin() || !$id) {
                $this->http401();

                return $this;
            }

            $object = $class_name::get($id);
            if (!$object) {
                $this->http404();

                return $this;
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
                'redirect' => '#'
            );


            return $this;
        }

    }


?>