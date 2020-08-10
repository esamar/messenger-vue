<?php

class reportes_controller extends Controller {

    function pre_accion() {
        if ($_SESSION['rol'] != 1)
            $this->redirect('?');
    }

    function index() {
        
    }

    function progreso() {
        $p = $this->model('usuario');
        $a['sedes'] = $p->getSedes();
        $this->datos($a);
    }

    function geties() {
        $p = $this->model('usuario');
        $id = $_POST['id'];
        $a = $p->getIes($id);
        $o = '';
        if(sizeof($a)>1){
            $o='<option value="0">Todas las IE</option>';
        }
        foreach ($a as $v) {
            $o = $o . '<option value="' . $v['id'] . '">' . $v['nombre'] . '</option>';
        }
        echo $o;
        $this->view(null);
    }

    function repprog() {
        $this->view(null);
        $ids = $_POST['s'];
        $idi = $_POST['i'];
        if ($ids == -1 || $idi == -1) {
            return;
        }
        $q = $this->model('reportes');
        if (empty($_SESSION['num_preg'])) {
            $_SESSION['num_preg'] = $q->getCantPreg();
        }
        $total = $q->getTotalUsuarios($ids, $idi);
        $completados = $q->getTotalCompletados(1, $ids, $idi);
        $pendiente = $q->getTotalCompletados(0, $ids, $idi); //echo $ids.'-'.$idi.'--'.$c;return;
        $noingreso = $total - $completados - $pendiente;
        if ($noingreso < 0) {
            $noingreso = 0;
        }

        //echo "$c#$p#$n#$t";
        $a['total'][0] = $completados;
        $a['total'][1] = $pendiente;
        $a['total'][2] = $noingreso;
        $a['total'][3] = $total;

        $m = $q->getUsuarioEstudiantes($ids, $idi);
        $r = '';
        $i = 1;
        foreach ($m as $v) {
            $s = $q->getEstadoID($v['id']);
            $r = $r .
                    '<tr>' .
                    '<td>' . $v['paterno'] . '</td>' .
                    '<td>' . $v['materno'] . '</td>' .
                    '<td>' . $v['nombre'] . '</td>' .
                    '<td>' . $v['sede'] . '</td>' .
                    '<td>' . $v['ie'] . '</td>' .
                    '<td>' . $v['grado'] . '</td>' .
                    '<td>' . $v['seccion'] . '</td>' .
                    '<td>' . $v['semana'] . '</td>' .
                    '<td>' . $s['estado'] . '</td>' .
                    '<td>' . $s['ini'] . '</td>' .
                    '<td>' . $s['fin'] . '</td>' .
                    '<td>' . $s['tiempo'] . '</td>' .
                    '<td>' . $s['avance'] . '</td>' .
                    '</tr>';
            $i++;
        }
        $a['tabla'] = $r;
        echo json_encode($a);
    }

    function usuarios() {
        $p = $this->model('usuario');
        $a['sedes'] = $p->getSedes();
        $this->datos($a);
    }

    function repuser() {
        $this->view(null);
        $ids = $_POST['s'];
        $idi = $_POST['i'];
        if ($ids == -1 || $idi == -1)
            return;
        $p = $this->model('reportes');
        $m = $p->getUsuarioEstudiantes($ids, $idi);
        $r = '';
        $i = 1;
        foreach ($m as $v) {
            $r = $r .
                    '<tr>' .
                    '<th scope="row">' . $i . '</th>' .
                    '<td>' . $v['user'] . '</td>' .
                    '<td>' . $v['paterno'] . '</td>' .
                    '<td>' . $v['materno'] . '</td>' .
                    '<td>' . $v['nombre'] . '</td>' .
                    '<td>' . $v['sede'] . '</td>' .
                    '<td>' . $v['ie'] . '</td>' .
                    '</tr>';
            $i++;
        }
        echo $r;
    }

    function preguntas() {
        $p = $this->model('reportes');
        $a = $p->getPregunta();
        $r = array();
        $r[0]['p'] = '0';
        $i = -1;
        $j = 0;
        foreach ($a as $v) {
            if ($v['titulo'] != $r[($i == -1) ? 0 : $i]['p']) {
                $i++;
                $r[$i]['tipo'] = $v['tipo'];
                $r[$i]['p'] = $v['titulo'];
                $r[$i]['c1'] = $v['col1'];
                $r[$i]['c2'] = $v['col2'];
                $r[$i]['c3'] = $v['col3'];
                $r[$i]['c4'] = $v['col4'];
                $j = 0;
            }
            $r[$i]['a'][$j] = $v['alternativa'];
            $j++;
        }
        $this->datos($r);
    }

    function analisis() {
        $p = $this->model('usuario');
        $a['sedes'] = $p->getSedes();
        $this->datos($a);
    }

    function exportar($ids, $idi) {
        $this->view(null);
        if ($ids == -1 || $idi == -1)
            return;
        $q = $this->model('reportes');
        if (empty($_SESSION['num_preg']))
            $_SESSION['num_preg'] = $q->getCantPreg();
        $m = $q->getUsuarioEstudiantes($ids, $idi);
        $r = '';
        $i = 1;
        foreach ($m as $v) {
            $s = $q->getEstadoID($v['id']);
            $r = $r .
                    '<tr>' .
                    '<td>' . utf8_decode($v['paterno']) . '</td>' .
                    '<td>' . utf8_decode($v['materno']) . '</td>' .
                    '<td>' . utf8_decode($v['nombre']) . '</td>' .
                    '<td>' . utf8_decode($v['sede']) . '</td>' .
                    '<td>' . utf8_decode($v['modular']) . '</td>' .
                    '<td>' . utf8_decode($v['ie']) . '</td>' .
                    '<td>' . utf8_decode($v['grado']) . '</td>' .
                    '<td>' . utf8_decode($v['seccion']) . '</td>' .
                    '<td>' . utf8_decode($v['semana']) . '</td>' .
                    '<td>' . utf8_decode($s['estado']) . '</td>' .
                    '<td>' . utf8_decode($s['ini']) . '</td>' .
                    '<td>' . utf8_decode($s['fin']) . '</td>' .
                    '<td>' . utf8_decode($s['tiempo']) . '</td>' .
                    '<td>' . utf8_decode($s['avance']) . '</td>' .
                    '</tr>';
            $i++;
        }
        $a['tabla'] = $r; 
        
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("content-disposition: attachment;filename=Reporte.xls");
        echo '<html><table border="1"><thead>' .
        '<tr><th>Ap.Paterno</th><th>Ap.Materno</th><th>Nombres</th><th>Sede</th>' .
        '<th>'.utf8_decode('Código modular').'</th><th>I.E</th><th>Grado</th><th>'.utf8_decode('Sección').'</th><th>Semana</th><th>Estado</th><th>Comenzado el</th><th>Finalizado</th><th>Tiempo requerido</th><th>Avance</th></tr></thead>' .
        '<tbody>' . $r . '</tbody></table></html>';
    }

    function exportar_resultados($ids, $idi) {//idsede, idie
        $this->view(null);
        //if($ids==-1 || $idi==-1) return;
        $ids = 0;
        $q = $this->model('reportes');
        if (empty($_SESSION['num_preg']))
            $_SESSION['num_preg'] = $q->getCantPreg();
        $m = $q->getUsuarioEstudiantes($ids, $idi);
        $r = '';
        $i = 1;
        foreach ($m as $v) {
            $s = $q->getResultados($v['id']);
            $r = $r .
                    '<tr>' .
                    '<td>' . utf8_decode($v['paterno']) . '</td>' .
                    '<td>' . utf8_decode($v['materno']) . '</td>' .
                    '<td>' . utf8_decode($v['nombre']) . '</td>' .
                    '<td>' . utf8_decode($v['sede']) . '</td>' .
                    '<td>' . utf8_decode($v['ie']) . '</td>' .
                    '<td>' . utf8_decode($v['grado']) . '</td>' .
                    '<td>' . utf8_decode($v['seccion']) . '</td>' .
                    '<td>' . utf8_decode($v['semana']) . '</td>';
            
            for ($i = 0; $i < $_SESSION['num_preg']; $i++) {
                $o = '';
                if (!empty($s[$i]['valor'])) {
                    $oo = explode('-', $s[$i]['valor']);
                    for ($j = 0; $j < sizeof($oo); $j++) {
                        $m = $q->toAlphabet($oo[$j]);
                        $o = $o . $m;
                        if ($j + 1 != sizeof($oo)) {
                            $o = $o . '-';
                        }
                    }
                }

                $r = $r . '<td>' . utf8_decode($o) . '</td>';
            }
            '</tr>';
            $i++;
        }
        $a['tabla'] = $r;
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("content-disposition: attachment;filename=Respuestas.xls");
        echo '<html><table border="1"><thead>' .
        '<tr><th>Ap.Paterno</th><th>Ap.Materno</th><th>Nombres</th><th>Sede</th>' .
        '<th>I.E</th><th>Grado</th><th>Seccion</th><th>Semana</th>';
        for ($i = 0; $i < $_SESSION['num_preg']; $i++) {
            echo '<th>Pregunta ' . ($i + 1) . '</th>';
        }
        echo '</tr></thead>' .
        '<tbody>' . $r . '</tbody></table></html>';
    }

}
