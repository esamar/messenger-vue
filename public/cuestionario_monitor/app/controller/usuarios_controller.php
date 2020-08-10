<?php

class usuarios_controller extends Controller
{ 
	function pre_accion(){if($_SESSION['rol']!=1) $this->redirect('?');}

	function index(){
		$p=$this->model('usuario');
		$a['users']=$p->getUsuarios();
		$a['sedes']=$p->getSedes();
		$this->datos($a);		
	}
        function regSupervisor(){
		$p=$this->model('usuario');
		$dni = $_POST['dni'];
		if($p->existeUser($dni)){
                    echo '0';
                    $this->view(null);
                    return false;
		}
		$nombres =$_POST['nom'];
		$paterno = $_POST['pat'];
		$materno = $_POST['mat'];
		$tipo =$_POST['tipo'];
		$pass =$_POST['p'];
                $sedes = $_POST['sedes'];
		if(trim($pass)=='') $pass = '123';
                $a=$p->registrarSedesSupervisor($dni,$nombres,$paterno,$materno,$pass, $sedes);
		echo '1';
		$this->view(null);
	}
	function registrar(){
		$p=$this->model('usuario');
		$dni = $_POST['dni'];
		if($p->existeUser($dni)){
                    echo '0';
                    $this->view(null);
                    return false;
		}
		$nombres =$_POST['nom'];
		$paterno = $_POST['pat'];
		$materno = $_POST['mat'];
		$tipo =$_POST['tipo'];
		$ie =$_POST['ie'];
                $grado ='NULL';
                $seccion ='NULL';
                $semana ='NULL';
		$pass =$_POST['p'];
		$sede =$_POST['sede'];
		if(trim($pass)=='') $pass = '123';
		$a=$p->registrarUsuarios($dni,$nombres,$paterno,$materno,$tipo,$ie,$pass,$sede,$grado,$seccion,$semana);
		echo '1';
		$this->view(null);
	}
        function actSupervisor(){
		$p=$this->model('usuario');
		$dni = $_POST['dni'];		
		$id =$_POST['id'];
		if($p->existeUserID($id,$dni)){
                    echo '0';
                    $this->view(null);
                    return;
		}
		$nombres =$_POST['nom'];
		$paterno = $_POST['pat'];
		$materno = $_POST['mat'];
                $sedes = $_POST['sedes'];
		$a=$p->actualizarUsuSupervisor($dni, $nombres, $paterno, $materno, $id, $sedes);
		echo '1';
		$this->view(null);
	}
	function actualizar(){
		$p=$this->model('usuario');
		$dni = $_POST['dni'];		
		$id =$_POST['idusuario'];
		if($p->existeUserID($id,$dni)){
                    echo '0';
                    $this->view(null);
                    return;
		}
		$nombres =$_POST['nom'];
		$paterno = $_POST['pat'];
		$materno = $_POST['mat'];
		$tipo =$_POST['tipo'];
		$ie =$_POST['ie'];
		$sede =$_POST['sede'];
		$a=$p->actualizarUsuario($dni,$nombres,$paterno,$materno,$tipo,$ie,$id,$sede);
		echo '1';
		$this->view(null);
	}

	function geties(){
		$p=$this->model('usuario');
		$id = $_POST['id'];
		$a=$p->getIes($id);
		$o='<option style="color:#ccc;" value="0">Institución educativa</option>';
		foreach ($a as $v) {
			$o=$o.'<option value="'.$v['id'].'">'.$v['nombre'].'</option>';
		}
		echo $o;
		$this->view(null);
	}
        function getsedes(){
		$p=$this->model('usuario');
		$id = $_POST['id'];
		$a=$p->getSedes_usuario2($id);
                $o=array();
                foreach ($a as $v){
                    $o[]=$v["idsede"];
                }
		echo json_encode($o);
		$this->view(null);
	}
	function getuser(){
		$p=$this->model('usuario');
		$id = $_POST['id'];
		$a=$p->getUser($id);
		
		$mm = explode('-',$a['completo']);

		$m=$a['id'].'-/-'.$a['idrol'].'-/-'.$a['nombre'].'-/-'.$mm[0].'-/-'.$mm[1].'-/-'.$mm[2].'-/-'.$mm[3].'-/-'.$mm[4].'-/-'.$a['idsede'];
		echo $m;
		$this->view(null);
	}

	function delete(){
		$p=$this->model('usuario');
		$id = $_POST['id'];
		$a=$p->deleteUser($id);
		$this->view(null);
	}

	function buscar(){
		$a='';
		$p=$this->model('usuario');
		$id = $_POST['p'];
		$c=$p->getUsuariosID($id);
     	
     	foreach ($c as $d) {
	        $a =$a.'<tr>
	          <td>'.$d['user'].'</td>
	          <td>'.$d['rol'].'</td>
	          <td>'.$d['nombres'].' '.$d['paterno'].' '.$d['materno'].'</td>
	          <th class="text-center"><a href="#" title="Editar" onclick="editarz('."'".$d['id']."'".')"><i class="zmdi zmdi-edit text-primary"></i></a></th>
	          <th class="text-center"><a href="#" title="Eliminar" data-toggle="modal" data-target="#toolabr_modal" onclick="mensajez('."'".$d['id']."','".$d['nombres'].' '.$d['paterno'].' '.$d['materno']."'".')"><i class="zmdi zmdi-delete text-danger"></i></a></th>
	        </tr>';
      	}
      echo $a;
      $this->view(null);
	}

	function buscars(){
		$a='';
		$p=$this->model('usuario');

		$c=$p->getUsuariosSupervisores();
     	
     	foreach ($c as $d) {
	        $a =$a.'<tr>
	          <td>'.$d['user'].'</td>
	          <td>'.$d['rol'].'</td>
	          <td>'.$d['nombres'].' '.$d['paterno'].' '.$d['materno'].'</td>
	          <th class="text-center"><a href="#" title="Editar" onclick="editarz('."'".$d['id']."'".')"><i class="zmdi zmdi-edit text-primary"></i></a></th>
	          <th class="text-center"><a href="#" title="Eliminar" data-toggle="modal" data-target="#toolabr_modal" onclick="mensajez('."'".$d['id']."','".$d['nombres'].' '.$d['paterno'].' '.$d['materno']."'".')"><i class="zmdi zmdi-delete text-danger"></i></a></th>
	        </tr>';
      	}
      echo $a;
      $this->view(null);
	}

	function cargar(){
		$tipo = $_FILES['fileInput']['type'];
		$destino = "tmp_excel.xlsx";
		if (@copy($_FILES['fileInput']['tmp_name'],$destino)) $this->redirect('?k=usuarios/excel');
		else echo '<script>alert("No se pudo cargar el archivo.");window.location.href = "?k=usuarios";</script>';
		$this->view(null);
	}

	function excel(){
		if (file_exists ("tmp_excel.xlsx")){ 
			require_once('libs/excel/PHPExcel.php');
			require_once('libs/excel/PHPExcel/Reader/Excel2007.php');

			$objReader = new PHPExcel_Reader_Excel2007();
			$objPHPExcel = $objReader->load("tmp_excel.xlsx");
			$objFecha = new PHPExcel_Shared_Date();       

			// Asignar hoja de excel activa
			$objPHPExcel->setActiveSheetIndex(0);

			$i=0;
			while (true) {
				$_DATOS_EXCEL[$i]['usuario'] = $objPHPExcel->getActiveSheet()->getCell('A'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['clave'] = $objPHPExcel->getActiveSheet()->getCell('B'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['nombres']= $objPHPExcel->getActiveSheet()->getCell('C'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['paterno']= $objPHPExcel->getActiveSheet()->getCell('D'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['materno']= $objPHPExcel->getActiveSheet()->getCell('E'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['ie']= $objPHPExcel->getActiveSheet()->getCell('F'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['modular'] = $objPHPExcel->getActiveSheet()->getCell('G'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['id'] = $objPHPExcel->getActiveSheet()->getCell('H'.($i+2))->getCalculatedValue();
				$_DATOS_EXCEL[$i]['sede'] = $objPHPExcel->getActiveSheet()->getCell('I'.($i+2))->getCalculatedValue();
                                $_DATOS_EXCEL[$i]['grado'] = $objPHPExcel->getActiveSheet()->getCell('J'.($i+2))->getCalculatedValue();
                                $_DATOS_EXCEL[$i]['seccion'] = $objPHPExcel->getActiveSheet()->getCell('K'.($i+2))->getCalculatedValue();
                                $_DATOS_EXCEL[$i]['semana'] = $objPHPExcel->getActiveSheet()->getCell('L'.($i+2))->getCalculatedValue();
				$i++;
				if(trim($objPHPExcel->getActiveSheet()->getCell('A'.($i+2))->getCalculatedValue())=='') break;
			}
			$g=$_DATOS_EXCEL;
			$p=$this->model('usuario');
			$g=$p->guardarExcel($_DATOS_EXCEL);
			$this->datos($g);
		}
	}

	function modelo(){
		header("Content-disposition: attachment; filename=Libro1.xlsx");
		header("Content-type: MIME");
		readfile("Libro1.xlsx");
		$this->view(null);
	}
}