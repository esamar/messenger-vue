<?php

class supervisor_controller extends Controller {

    function pre_accion() {
        if ($_SESSION['rol'] != 2)
            $this->redirect('?');
    }

    function index() {
        
    }

    function incidentes($v) {


        $tipo = $v;

        // $p = $this->model('incidentes');
        // $a['sedes'] = $p->getSedes_usuario($_SESSION['id_user']);

        // $this->datos($a);

        $p = $this->model('usuario');

        $a['tipo'] = $tipo;
        
        $a['sedes'] = $p->getSedes_usuario($_SESSION['id_user']);

//    $b=$p->getIes($a['id']);
//    $o='<option style="color:#ccc;" value="-1">Instituci��n educativa</option><option value="0">Todo</option>';
//    foreach ($b as $v) {
//      $o=$o.'<option value="'.$v['id'].'">'.$v['nombre'].'</option>';
//    }
//    $a['ies']=$o;
// var_dump($a);

        $this->datos($a);


    }

    function progreso() {

        $p = $this->model('usuario');
        $a['sedes'] = $p->getSedes_usuario($_SESSION['id_user']);
//    $b=$p->getIes($a['id']);
//    $o='<option style="color:#ccc;" value="-1">Instituci��n educativa</option><option value="0">Todo</option>';
//    foreach ($b as $v) {
//      $o=$o.'<option value="'.$v['id'].'">'.$v['nombre'].'</option>';
//    }
//    $a['ies']=$o;
// var_dump($a);
        $this->datos($a);
    }

    /*
      function usuarios(){
      $p=$this->model('usuario');
      $a=$p->getSede($_SESSION['id_user']);

      $b=$p->getIes($a['id']);
      $o='<option style="color:#ccc;" value="-1">Instituci��n educativa</option><option value="0">Todo</option>';
      foreach ($b as $v) {
      $o=$o.'<option value="'.$v['id'].'">'.$v['nombre'].'</option>';
      }
      $a['ies']=$o;

      $this->datos($a);
      }
     */

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

function historial_incidente() {

    $this->view(null);

    $idpersona = $_POST['id'];

    $tipo = $_POST['tipo'];

    $q = $this->model('incidentes');

    $m = $q->getHistoria($idpersona , $tipo );
    
    $r = ""; 
    
    $i = 1;

    foreach ($m as $v) 
    {

      $boton = $_SESSION['linea'] == "2" ? "<div class='est-reg-tb ini'> Linea 1 <button onclick='atenderIncidente(" . $v['incidente'] . ");' style='color:black!important'> Atender </button></div>" : "No";

      $editar = '<a href="#" title="Editar" style="margin:0 10px;" onclick="formIncidente(' . $idpersona . ',1 ,' . $v['incidente'] . ')"><i class="zmdi zmdi-edit text-primary"></i></a>';

      $r = $r . '<tr>' .
          '<td>' . $i . '</td>' .
          '<td>' . $v['fecha_hora'] . '</td>' .
          '<td>' . $v['problema'] . '</td>' .
          '<td>' . $v['observaciones'] . '</td>' .
          '<td>' . $v['acciontomada'] . '</td>' .
          '<td>' . ( (int)$v['solucionado'] === 1 ? 'Si' : ( (int)$v['solucionado'] === 2 ? 'No' : 'Sin respuesta' ) ) . '</td>' .
          '<td>' . ( (int)$v['linea02'] === 1 ? 'Si' : ( (int)$v['linea02'] === 2 ? 'No' : 'Sin respuesta' ) ). '</td>' .

          '<td>' . ( (int)$v['linea02'] === 1 && (int)$v['solucionado_l2'] === 1 ? 'Si' :  ( (int)$v['linea02'] !== 1 ? '-' : $boton  ) ) . '</td>' .
          '<td>' . ( $v['cambio_fecha'] == '00/00/0000' ? 'No' : $v['cambio_fecha'] ) . '</td>' .
          '<td>' . ( (int)$_SESSION['id_user'] === (int)$v['idregistrador'] ? $editar : '' ). '</td>' .
          '</tr>';
        
        $i++;

    }

    echo $r;

}

function consultaIncidente() {

    $this->view(null);

    $idpersona = $_POST['id'];

    $tipo = $_POST['tipo'];

    $q = $this->model('incidentes');

    $m = $q->getConsultaHistoria($idpersona , $tipo );
    
    $r = ""; 
    
    $i = 1;

    foreach ($m as $v) 
    {

        $r = $r.
                '<tr>' .
                '<td>' . $v['fecha_hora'] . '</td>' .
                '<td>' . $v['problema'] . '</td>' .
                '<td>' . $v['observaciones'] . '</td>' .
                '<td>' . $v['acciontomada'] . '</td>' .
                '<td>' . ( $v['cambio_fecha'] == '00/00/0000' ? 'No' : $v['cambio_fecha'] ). '</td>' .
                '</tr>';
        
        $i++;

    }

    echo json_encode( array( 'tabla' => $r , 'data' => $m )  );

}

    function rep_reg_incidente() {
        
        $q = $this->model('reportes');

        $q->getEstadoExt('123');
exit();

        $this->view(null);
        $ids = $_POST['s'];
        $idi = $_POST['i'];
        $tipo = $_POST['tipo'];

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

        $m = $q->getUsuarioEstudiantes($ids, $idi, $tipo);

        $r = '';
        $i = 1;
        foreach ($m as $v) 
        {
            $s = $q->getEstadoID( $v['id'] , $tipo );

            $nombre = $v['paterno'] . ' '. $v['materno'] . ', ' . $v['nombre'];

            if ( $_SESSION['linea'] == "1" )
            {

              $r = $r .
                      '<tr>' .
                      '<td>' . substr($v['sede'],0,2) . '</td>' .
                      '<td>' . $nombre  . '</td>' .
                      '<td>' . $v['ie'] . '</td>' .
                      '<td>' . $v['fecha_participacion'] . '</td>' .
                      '<td>' . $s['estado'] . '</td>' .
                      '<td>' . $s['avance'] . '</td>' .
                      '<td><button onclick="historiaIncidente(' . $v['idpersona'] . ', \'[ IE: ' . $v['ie'] . '  -  ' . $nombre . ' ]\');">Registrar incidente</button></td>' .
                      '</tr>';
                      // '<td>' . $s['avance'] . '</td>' .

            }
            else if ( $_SESSION['linea'] == "2" )
            {

              $r = $r .
                      '<tr>' .
                      '<td>' . substr($v['sede'],0,2) . '</td>' .
                      '<td>' . $nombre . '</td>' .
                      '<td>' . $v['ie'] . '</td>' .
                      '<td>' . $v['fecha_participacion'] . '</td>' .
                      '<td>' . $s['estado'] . '</td>' .
                      '<td>' . $s['avance'] . '</td>' .

                      '<td>' . ( $v['linea_atencion'] == "1" ? '<div class="est-reg-tb ini"> Linea 1 <button onclick="atenderIncidente(' . $v['incidente'] . ');" style="color:black!important">Atender</button>' : '' ) .'</div></td>' .

                      '<td><button onclick="historiaIncidente(' . $v['idpersona'] . ', \'[ IE: ' . $v['ie'] . '  -  ' . $nombre . ' ]\');">Registrar incidente</button></td>' .
                      
                      '</tr>';

                      // '<td><button onclick="atenderIncidente(' . $v['incidente'] . ' );">Atender incidente</button></td>' .
                      // '<td>' . $s['tiempo'] . '</td>' .
            }

            $i++;
        

        }

        $a['tabla'] = $r;

        echo json_encode($a);
    }

//	function repprog(){		 
//		$this->view(null);
//		$ids=$_POST['s'];
//		$idi=$_POST['i'];
//		if($ids==-1 || $idi==-1) return;
//		$q=$this->model('reportes');
//		if(empty($_SESSION['num_preg'])) $_SESSION['num_preg']=$q->getCantPreg();
//		$t = $q->getTotalUsuarios($ids,$idi);
//		$c = $q->getTotalCompletados(1,$ids,$idi);
//		$p = $q->getTotalCompletados(0,$ids,$idi);//echo $ids.'-'.$idi.'--'.$c;return;
//		$n = $t-$c-$p;
//		if($n<0) $n=0;
//		$a['total'][0]=$c;
//		$a['total'][1]=$p; 
//		$a['total'][2]=$n;
//		$a['total'][3]=$t;
//
//
//		$m = $q->getUsuarioEstudiantes($ids,$idi);
//		$r='';$i=1;
//		foreach ($m as $v) {
//			$s = $q->getEstadoID($v['id']);
//			$r=$r.
//			'<tr>'.			
//			    '<td>'.$v['paterno'].'</td>'.
//			    '<td>'.$v['materno'].'</td>'.
//			    '<td>'.$v['nombre'].'</td>'.
//			    '<td>'.$v['sede'].'</td>'.
//			    '<td>'.$v['ie'].'</td>'.
//			    '<td>'.$s['estado'].'</td>'.
//			    '<td>'.$s['ini'].'</td>'.
//			    '<td>'.$s['fin'].'</td>'.
//			    '<td>'.$s['tiempo'].'</td>'.
//			    '<td>'.$s['avance'].'</td>'.
//			'</tr>';
//			$i++;
//		}
//		$a['tabla']=$r;
//		echo json_encode($a);
//	}
    /*
      function repuser(){
      $this->view(null);
      $ids=$_POST['s'];
      $idi=$_POST['i'];
      if($ids==-1 || $idi==-1) return;
      $p=$this->model('reportes');
      $m = $p->getUsuarioEstudiantes($ids,$idi);
      $r='';$i=1;
      foreach ($m as $v) {
      $r=$r.
      '<tr>'.
      '<th scope="row">'.$i.'</th>'.
      '<td>'.$v['user'].'</td>'.
      '<td>'.$v['paterno'].'</td>'.
      '<td>'.$v['materno'].'</td>'.
      '<td>'.$v['nombre'].'</td>'.
      '<td>'.$v['sede'].'</td>'.
      '<td>'.$v['ie'].'</td>'.
      '</tr>';
      $i++;
      }
      echo $r;
      }

      function preguntas(){
      $p=$this->model('reportes');
      $a = $p->getPregunta();
      $r = array();
      $r[0]['p']='0';
      $i=0;$i=-1;$j=0;
      foreach ($a as $v) {
      if($v['titulo']!=$r[($i==-1)?0:$i]['p']){
      $i++;
      $r[$i]['p']=$v['titulo'];
      $r[$i]['c1']=$v['col1'];
      $r[$i]['c2']=$v['col2'];
      $r[$i]['c3']=$v['col3'];
      $r[$i]['c4']=$v['col4'];
      $j=0;
      }
      $r[$i]['a'][$j]=$v['alternativa'];
      $j++;
      }
      $this->datos($r);
      }
     */
    function exportar($ids, $idi , $tipo) 
    {

        $this->view(null);

        if ($ids == -1 || $idi == -1)
        {

            return;
        
        }

        $q = $this->model('reportes');

        if (empty($_SESSION['num_preg']))
        {

            $_SESSION['num_preg'] = $q->getCantPreg();
        
        }

        // $m = $q->getUsuarioEstudiantes($ids, $idi);
        $m = $q->getReporteIncidentes($ids, $idi , $tipo );

        $r = '';

        $i = 1;

        foreach ($m as $v) 
        {
            
            $s = $q->getEstadoID($v['id'] , $tipo);

            $r = $r .
              '<tr>' .
              '<td>' . $v['codigo_monitor'] . '</td>'.
              '<td>' . utf8_decode($v['dre']) . '</td>'.
              '<td>' . utf8_decode($v['ugel']) . '</td>'.
              '<td>\'' . utf8_decode($v['modular']) . '</td>'. 
              '<td>' . utf8_decode($v['nombre']) . '</td>'.
              '<td>' . utf8_decode($v['rol']) . '</td>'.
              '<td>' . utf8_decode($v['appaterno']) . ' ' . utf8_decode($v['apmaterno']) . '</td>'. 
              '<td>' . utf8_decode($v['nombres']) . '</td>'. 
              '<td>' . utf8_decode($v['grado_seccion']) . '</td>'.
              '<td>' . utf8_decode($v['telefono']) . '</td>'.
              '<td>\'' . utf8_decode($v['fecha_participacion']) . '</td>'.
              '<td>' . utf8_decode($s['avance']) . '</td>' .
              '<td>' . utf8_decode($s['estado']) . '</td>' .
              '<td>' . utf8_decode($v['linea_1']) . '</td>'.
              '<td>' . utf8_decode($v['problema']) . '</td>'.
              '<td>' . utf8_decode($v['observaciones']) . '</td>'.
              '<td>' . utf8_decode($v['acciontomada']) . '</td>'.
              '<td>' . utf8_decode($v['solucionado_l1']) . '</td>'.
              '<td>' . utf8_decode($v['linea_2']) . '</td>'.
              '<td>' . utf8_decode($v['observaciones_l2']) . '</td>'.
              '<td>' . utf8_decode($v['acciontomada_l2']) . '</td>'.
              '<td>' . utf8_decode($v['solucionado_l2']) . '</td>'.
              '</tr>';
            $i++;
        
        }

        $a['tabla'] = $r;

        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");

        header("Expires: 0");
        
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        
        header("content-disposition: attachment;filename=Reporte_incidentes.xls");
        
        echo '<html><table border="1">
                  <thead>
                    <th>MONITOR</th>
                    <th>DRE</th>
                    <th>UGEL</th>
                    <th>'. utf8_decode('CÓDIGO MÓDULAR') .'</th> 
                    <th>IE</th>
                    <th>ROL DEL USUARIO</th>
                    <th>APELLIDOS</th> 
                    <th>NOMBRES</th> 
                    <th>'. utf8_decode('GRADO_SECCIÓN') .'</th>
                    <th>'. utf8_decode('NÚMERO_CONTACTO') .'</th>
                    <th>FECHA_COMPROMISO</th>
                    <th>AVANCE</th>
                    <th>ESTADO</th>
                    <th>'. utf8_decode('LINEA_ATENCIÓN_1') .'</th>
                    <th>INCIDENTE</th>
                    <th>OBSERVACIONES</th>
                    <th>'. utf8_decode('ACCIÓN_TOMADA') .'</th>
                    <th>'. utf8_decode('SOLUCIÓN') .'</th>
                    <th>'. utf8_decode('LINEA_ATENCIÓN_2') .'</th>
                    <th>OBSERVACIONES</th>
                    <th>'. utf8_decode('ACCIÓN_TOMADA') .'</th>
                    <th>'. utf8_decode('SOLUCIÓN') .'</th>
                  </thead>' .
                  '<tbody>' . $r . '</tbody></table></html>';

    }
//    function exportar($ids, $idi) {
//        $this->view(null);
//        if ($ids == -1 || $idi == -1)
//            return;
//        $q = $this->model('reportes');
//        if (empty($_SESSION['num_preg']))
//            $_SESSION['num_preg'] = $q->getCantPreg();
//        $m = $q->getUsuarioEstudiantes($ids, $idi);
//        $r = '';
//        $i = 1;
//        foreach ($m as $v) {
//            $s = $q->getEstadoID($v['id']);
//            $r = $r .
//                    '<tr>' .
//                    '<td>' . $v['paterno'] . '</td>' .
//                    '<td>' . $v['materno'] . '</td>' .
//                    '<td>' . $v['nombre'] . '</td>' .
//                    '<td>' . $v['sede'] . '</td>' .
//                    '<td>' . $v['ie'] . '</td>' .
//                    '<td>' . $s['estado'] . '</td>' .
//                    '<td>' . $s['ini'] . '</td>' .
//                    '<td>' . $s['fin'] . '</td>' .
//                    '<td>' . $s['tiempo'] . '</td>' .
//                    '<td>' . $s['avance'] . '</td>' .
//                    '</tr>';
//            $i++;
//        }
//        $a['tabla'] = $r;
//        header("Content-Type: application/vnd.ms-excel");
//        header("Expires: 0");
//        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//        header("content-disposition: attachment;filename=Reporte.xls");
//        echo '<html><table border="1"><thead>' .
//        '<tr><th>Ap.Paterno</th><th>Ap.Materno</th><th>Nombres</th><th>Sede</th>' .
//        '<th>I.E</th><th>Estado</th><th>Comenzado el</th><th>Finalizado</th><th>Tiempo requerido</th><th>Avance</th></tr></thead>' .
//        '<tbody>' . $r . '</tbody></table></html>';
//    }

    function geties() 
    {
        
        $p = $this->model('usuario');
        
        $id = $_POST['id'];
        
        $a = $p->getIes($id);
        
        $o = '';
        
        if (sizeof($a) > 1) {
    
            $o = '<option value="0">Todas las IE</option>';
    
        }
        
        foreach ($a as $v) {
    
            $o = $o . '<option value="' . $v['id'] . '">' . $v['nombre'] . '</option>';
    
        }
        
        echo $o;
        
        $this->view(null);

    }


    function setIncidente()
    {
        $p = $this->model('incidentes');

        $p -> setIncidente($_POST['data'] , $_POST['tipo'] , $_POST['actualiza'] , $_POST['idincidencia'] );

        $this->view(null);

    }

    function setAtencionIncidente()
    {
        $p = $this->model('incidentes');

        $p -> setAtencionIncidente($_POST['data'] , $_POST['tipo'] );

        $this->view(null);

    }

}
