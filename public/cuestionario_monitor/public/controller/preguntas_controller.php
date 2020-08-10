<?php

class preguntas_controller extends Controller {

    function pre_accion() {
        if ($_SESSION['rol'] != 1)
            $this->redirect('?');
    }

    function index() {
        $q = $this->model('cuestionario');
        $p = $q->getPreguntas();
        $this->datos($p);
    }

    function pregunta() {
        $tipo = $_POST['tipo'];
        if ($tipo == 1) {
            $id = $_POST['data'];
            $q = $this->model('cuestionario');
            $p = $q->getPreguntaID($id);
            $r = $p['titulo'] . '-/-' . $p['col1'] . '-/-' . $p['col2'] . '-/-' . $p['col3'] . '-/-' . $p['col4'];
            foreach ($p['alt'] as $v) {
                $r = $r . '-/-' . $v;
            }
            echo $r;
        } elseif ($tipo == 2) {
            $id = $_POST['data'];
            $q = $this->model('cuestionario');
            $p = $q->getPreguntaID_OM($id);
            $r = $p['titulo'];
            foreach ($p['alt'] as $v) {
                $r = $r . '-/-' . $v;
            }
            echo $r;
        } elseif ($tipo == 3) {
            $id = $_POST['data'];
            $q = $this->model('cuestionario');
            $p = $q->getPreguntaID_PA($id);
            $r = $p['titulo'];
            echo $r;
        }
        $this->view(null);
    }

    function eliminar() {
        $q = $this->model('cuestionario');
        $id = $_POST['data'];
        $p = $q->eliminarPregunta($id);
        $this->view(null);
    }

    function registrar() {
        $p = $this->model('cuestionario');
        $titulo = $_POST['titulo'];
        $col1 = $_POST['col1'];
        $col2 = $_POST['col2'];
        $col3 = $_POST['col3'];
        $col4 = $_POST['col4'];
        $alt = explode('-/-', $_POST['alt']);

        if ($_POST['idp'] == 0) { //insert
            if ($_POST['tipo'] == 1) {
                $p->registrarPreguntaMA($titulo, $col1, $col2, $col3, $col4, $alt);
            } elseif ($_POST['tipo'] == 2) {
                $p->registrarPreguntaOM($titulo, $alt);
            } elseif ($_POST['tipo'] == 3) {
                $p->registrarPreguntaPA($titulo);
            }
        } else { //update
            if ($_POST['tipo'] == 1) {
                $idpreg = $_POST['idp'];
                $p->actualizarPreguntaMA($titulo, $col1, $col2, $col3, $col4, $alt, $idpreg);
            } elseif ($_POST['tipo'] == 2) {
                $idpreg = $_POST['idp'];
                $p->actualizarPreguntaOM($titulo, $alt, $idpreg);
            } elseif ($_POST['tipo'] == 3) {
                $idpreg = $_POST['idp'];
                $p->actualizarPreguntaPA($titulo, $idpreg);
            }
        }

        $this->view(null);
    }

}
