<?php

class cuestionario_controller extends Controller {

    function pre_accion() {
        if ($_SESSION['rol'] != 3) {
            $this->redirect('?');
        }
    }

    function index() {
        $p = $this->model('cuestionario');
        if ($p->esIni()) {
            $this->redirect('?k=cuestionario/inicio/1');
        } else {
            $this->redirect('?k=cuestionario/instrucciones');
        }
    }

    function instrucciones() {
        $p = $this->model('cuestionario');
        if (empty($_SESSION['num_preg'])) {
            $_SESSION['num_preg'] = $p->getCantPreg();
        }
        $a = $p->esIni();
        $this->datos($a);
    }

    function inicio($n) {
        $p = $this->model('cuestionario');
        if (empty($_SESSION['num_preg'])) {
            $_SESSION['num_preg'] = $p->getCantPreg();
        }
        $num_preg = $_SESSION['num_preg'];

        //Calcula el avance para mostrar la pantalla final
        $a['a'] = $p->getAvance();
        $a['b'] = $num_preg - $a['a'];
        if ($a['b'] == 0) {
            $this->redirect('?k=estado/fin');
        }

        if ($n == '') {
            $n = 1;
        }
        //	$p = $this->model('cuestionario');
        if (!$p->esIni()) {
            $this->redirect('?k=cuestionario/instrucciones');
        }

        //	if(empty($_SESSION['num_preg'])) $_SESSION['num_preg']=$p->getCantPreg();

        if ($n > $_SESSION['num_preg']) {
            $this->redirect('?k=estado/fin');
            return;
        }
        //$a['valorN']=$n;
        $a['p'] = $p->getPregunta($n); //se obtiene la pregunta y sus opciones

        if ($a['p']['tipo'] == 1) {

            for ($i = 0; $i < sizeof($a['p']['alt']); $i++) {
                $a['r'][$i][0] = '';
                $a['r'][$i][1] = '';
                $a['r'][$i][2] = '';
                $a['r'][$i][3] = '';
                $a['r'][$i][4] = '';
                $a['r'][$i][5] = '';
            }
        } else if ($a['p']['tipo'] == 2) { 
            for ($i = 0; $i < sizeof($a['p']['alt']); $i++) {
                $a['r'][$i] = '';
            }
        } else if ($a['p']['tipo'] == 3) { 
          
        }

        $a['b'] = false;
        $r = $p->getRespuesta($n); //se obtienen las respuestas
        if (sizeof($r) > 0) {
            $a['b'] = true;
            if ($a['p']['tipo'] == 1) {
                $rr = explode('-', $r[0]['valor']);
                for ($i = 0; $i < sizeof($a['p']['alt']); $i++) {
                    $a['r'][$i][$rr[$i] - 1] = 'checked';
                }
            } else if ($a['p']['tipo'] == 2) {
                $rr = intval($r[0]['valor']);
                for ($i = 0; $i < sizeof($a['p']['alt']); $i++) {
                    if ($i == $rr - 1) {
                        $a['r'][$i] = 'checked';
                    }
                }
            } else if ($a['p']['tipo'] == 3) {
              $a['r'] = $r[0]['valor'];
            }
        }

        $this->datos($a);
    }

    function r() { //v=respuestas n=idpreg
        if (empty($_POST['v'])) {
            return;
        }
      
        $val = substr($_POST['v'], 0, strlen($_POST['v']) - 1);
      
        $n = $_POST['n'];
        $p = $this->model('cuestionario');
        if ($n == 0) {
            $val = 0;
        }
        if (empty($_SESSION['num_preg'])) {
            $_SESSION['num_preg'] = $p->getCantPreg();
        }
//        echo 'idpreg:'.$n.'.....respuestas:'.$val;
//        return false;
        $p->registrarAvance($n, $val);
        $this->view(null);
    }

    /*
      function truncate(){
      $p = $this->model('cuestionario');
      $p->trun();
      $this->redirect('?k=cuestionario');
      }
     */
}
