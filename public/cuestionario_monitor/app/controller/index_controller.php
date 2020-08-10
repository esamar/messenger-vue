<?php

class index_controller extends Controller {

    public function login() {
        $this->template(null);
    }

    public function index() {
        switch ($_SESSION['rol']) {
            case 1: {
                    $this->redirect('?k=reportes');
                }break;
            case 2: {
                    $this->redirect('?k=supervisor');
                }break;
            case 3: {
                    $this->redirect('?k=cuestionario');
                }break;
        }
    }

    public function validar() {
        $user = $this->model('usuario');

        $datos = $user->login($_POST['usuario'], $_POST['password']);

        if ($datos !== false) {
            if (session_status() == PHP_SESSION_NONE)
                session_start();

            $_SESSION['ini'] = true;
            echo '1';
        } else
            echo '0';
        $this->view(null);
    }

    function salir() {
        session_destroy();
        $this->redirect('?');
        $this->view(null);
    }

    public function cambiar() {
        
    }

    public function c() {
        $p1 = $_POST['p1'];
        $p2 = $_POST['p2'];
        $p = $this->model('usuario');
        $z = $p->validPass($p1);
        echo $z . '-0';
        if ($z == 1) {
            $p->cambiaPass($p1, $p2);
        }
        $this->view(null);
    }

}
