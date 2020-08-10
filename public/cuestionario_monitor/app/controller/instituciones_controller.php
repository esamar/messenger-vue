<?php

class instituciones_controller extends Controller {

    function pre_accion() {
        if ($_SESSION['rol'] != 1)
            $this->redirect('?');
    }

    function index() {
        $p = $this->model('instituciones');
        $a = array();
        $a['sedes'] = $p->getSedes();
        $this->datos($a);
    }

    function getinstituciones() {
        $this->view(null);
        $ids = $_POST['sede'];
        if ($ids == -1) {
            return;
        }
        $q = $this->model('instituciones');
        $m = $q->getInstituciones2($ids);
        $r = '';
        foreach ($m as $d) {
            $sede = explode('-/-',$d['sede']);
            $r = $r .
                    '<tr>' .
                        '<td>' . $sede[1] . '</td>' .
                        '<td>' . $d['modular'] . '</td>' .
                        '<td>' . $d['nombre'] . '</td>' .
                        '<th class="text-center">
                            <a href="#" title="Editar" style="margin:0 10px;" onclick="mostrarRegIe();editarIE(\''.$d['id'].'\',\''.$sede[0].'\',\''.$d['modular'].'\',\''.$d['nombre'].'\')">
                                <i class="zmdi zmdi-edit text-primary"></i>
                            </a>
                            <a href="#" title="Eliminar" style="margin-left: 10px;" data-toggle="modal" data-target="#toolabr_modal" onclick="alertaEliminarIE(\''.$d['id'].'\',\''.$d['nombre'].'\')">
                                <i class="zmdi zmdi-delete text-danger"></i>
                            </a>
                        </th>'.
                    '</tr>';
        }
        $r;
        echo json_encode($r);
    }

    function regsede() {
        $idsede = $_POST['sedeid'];
        $sede = $_POST['sede'];
        if (strlen($idsede) == 1)
            $idsede = '0' . $idsede;
        $p = $this->model('instituciones');
        $a = $p->regSede($idsede, $sede);
        $this->view(null);
    }

    function actsede() {
        $id = $_POST['idsede'];
        $idsede = $_POST['sedeid'];
        $sede = $_POST['sede'];
        if (strlen($idsede) == 1)
            $idsede = '0' . $idsede;
        $p = $this->model('instituciones');
        $a = $p->actSede($id, $idsede, $sede);
        $this->view(null);
    }

    function reginstitucion() {
        $id = $_POST['id'];
        $mod = $_POST['mod'];
        $ie = $_POST['ie'];
        $p = $this->model('instituciones');
        if ($p->existeIE($mod)) {
            echo '0';
            return;
        }
        $a = $p->regInst($id, $mod, $ie);
        echo '1';
        $this->view(null);
    }

    function actinstitucion() {
        $id = $_POST['id'];
        $idsede = $_POST['idsede'];
        $mod = $_POST['mod'];
        $ie = $_POST['ie'];
        $p = $this->model('instituciones');
        if ($p->existeIEID($id, $mod)) {
            echo '0';
            return;
        }
        $a = $p->actInst($id, $idsede, $mod, $ie);
        echo '1';
        $this->view(null);
    }

    function delsede() {
        $id = $_POST['data'];
        $p = $this->model('instituciones');
        $a = $p->delsed($id);
        $this->view(null);
    }

    function delinstitucion() {
        $id = $_POST['data'];
        $p = $this->model('instituciones');
        $a = $p->delinstitucion($id);
        $this->view(null);
    }

}
