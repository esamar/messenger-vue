<?php
class instituciones_model extends Model {
	public function getSedes(){
		$s=$this->consulta("SELECT * FROM 37978_sede WHERE estado=1 ORDER BY sedeid ASC");
		return $s;
	}

	public function getIstituciones(){
		$s=$this->consulta("SELECT *,(SELECT CONCAT(id,'-/-',sedeid,' - ',nombre) FROM 37978_sede WHERE estado=1 AND id = 37978_institucion.idsede) as sede FROM 37978_institucion WHERE estado=1");
		return $s;
	}
        public function getInstituciones2($idsede){
                if($idsede==0){
                    $s=$this->consulta("SELECT *,(SELECT CONCAT(id,'-/-',sedeid,' - ',nombre) FROM 37978_sede WHERE estado=1 AND id = 37978_institucion.idsede) as sede FROM 37978_institucion WHERE estado=1 ORDER BY sede, nombre");
                }else{
                    $s=$this->consulta("SELECT *,(SELECT CONCAT(id,'-/-',sedeid,' - ',nombre) FROM 37978_sede WHERE estado=1 AND id = 37978_institucion.idsede) as sede FROM 37978_institucion WHERE estado=1 AND 37978_institucion.idsede='$idsede' ORDER BY sede, nombre");
                }
		return $s;
	}
	public function regSede($idsede,$sede){
		$this->sentencia("INSERT INTO 37978_sede(sedeid,nombre,estado) VALUES ('$idsede','$sede',1)");
	}

	public function actSede($id,$idsede,$sede){
		$this->sentencia("UPDATE 37978_sede SET sedeid = '$idsede',nombre='$sede' WHERE estado=1 AND id = $id");
	}

	public function existeIE($mod){
		$s=$this->consulta("SELECT COUNT(id) as n FROM 37978_institucion WHERE modular=$mod AND estado =1" )[0]['n'];
		if($s==0)
			return false;
		else
			return true;
	}

	public function existeIEID($id,$mod){
		$s=$this->consulta("SELECT COUNT(id) as n FROM 37978_institucion WHERE modular=$mod AND estado =1 AND id<>$id" )[0]['n'];
		if($s==0)
			return false;
		else
			return true;
	}

	public function regInst($id,$mod,$ie){

		$this->sentencia("INSERT INTO 37978_institucion (modular,nombre,idsede,estado) VALUES ('$mod','$ie',$id,1)");
	}

	public function actInst($id,$idsede,$mod,$ie){
		$this->sentencia("UPDATE 37978_institucion SET idsede=$idsede,nombre='$ie',modular='$mod' WHERE  estado=1 AND id=$id");
	}

	public function delinstitucion($id){
		$this->sentencia("UPDATE 37978_institucion SET estado=0 WHERE estado=1 AND id = $id");
	}

	public function delsed($id){
		$this->sentencia("UPDATE 37978_sede SET estado=0 WHERE estado=1 AND id = $id");
	}
}
