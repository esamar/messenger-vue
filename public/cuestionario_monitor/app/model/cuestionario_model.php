<?php

class cuestionario_model extends Model {

    public function getCantPreg() {
        $p = $this->consulta("SELECT count(id) as count FROM 37978_pregunta WHERE estado = 1");
        return $p[0]['count'];
    }

    public function esIni() {
        $iduser = $_SESSION['id_user'];
        $p = $this->consulta("SELECT * FROM 37978_avance WHERE idusuario=$iduser AND idpregunta=0");
        if (sizeof($p) > 0)
            return true;
        return false;
    }

    public function getRespuesta($num) {
        $p = $this->consulta("SELECT * FROM 37978_pregunta WHERE estado = 1 ORDER BY id ASC");
        $idpreg = $p[$num - 1]['id'];
        $iduser = $_SESSION['id_user'];
        $p = $this->consulta("SELECT * FROM 37978_avance WHERE idusuario=$iduser AND idpregunta=$idpreg");
        return $p;
    }

    public function getPregunta($num) {
        $p = $this->consulta("SELECT * FROM 37978_pregunta WHERE estado = 1 ORDER BY id ASC");
        $idpreg = $p[$num - 1]['id'];
        $a = array();
        $a['id'] = $p[$num - 1]['id'];
        $a['numero'] = $num;
        $a['titulo'] = $num . '. ' . $p[$num - 1]['titulo'];
        $a['tipo'] = $p[$num - 1]['tipo'];

        if ($a['tipo'] == 1) {
            $idcol = $p[$num - 1]['idcolumna'];
            $q = $this->consulta("SELECT * FROM 37978_columna WHERE id=$idcol ORDER BY id ASC");

            $a['col1'] = $q[0]['col1'];
            $a['col2'] = $q[0]['col2'];
            $a['col3'] = $q[0]['col3'];
            $a['col4'] = $q[0]['col4'];
            $a['col5'] = $q[0]['col5'];
            $a['col6'] = $q[0]['col6'];
        }

        if ($a['tipo'] != 3) { //si NO es pregunta abierta

            $m = $this->consulta("SELECT * FROM 37978_alternativa WHERE idpregunta = $idpreg AND estado = 1 ORDER BY id ASC");
            for ($i = 0; $i < sizeof($m); $i++) {
                $a['alt'][$i] = $num . '.' . ($i + 1) . ' ' . $m[$i]['alternativa'];
            }
        }

        return $a;

    }

    public function getPreguntaID($id) {
        $p = $this->consulta("SELECT * FROM 37978_pregunta WHERE estado = 1 AND id=$id ORDER BY id ASC");

        $idpreg = $p[0]['id'];
        $a = array();
        $a['titulo'] = $p[0]['titulo'];
        $idcol = $p[0]['idcolumna'];

        $q = $this->consulta("SELECT * FROM 37978_columna WHERE id=$idcol ORDER BY id ASC");
        $a['col1'] = $q[0]['col1'];
        $a['col2'] = $q[0]['col2'];
        $a['col3'] = $q[0]['col3'];
        $a['col4'] = $q[0]['col4'];
        $a['col5'] = $q[0]['col5'];
        $a['col6'] = $q[0]['col6'];

        $m = $this->consulta("SELECT * FROM 37978_alternativa WHERE idpregunta = $idpreg AND estado = 1 ORDER BY id ASC");
        for ($i = 0; $i < sizeof($m); $i++) {
            $a['alt'][$i] = $m[$i]['alternativa'];
        }

        return $a;
    }

    public function getPreguntaID_OM($id) {
        $p = $this->consulta("SELECT * FROM 37978_pregunta WHERE estado = 1 AND id=$id ORDER BY id ASC");

        $idpreg = $p[0]['id'];
        $a = array();
        $a['titulo'] = $p[0]['titulo'];

        $m = $this->consulta("SELECT * FROM 37978_alternativa WHERE idpregunta = $idpreg AND estado = 1 ORDER BY id ASC");
        for ($i = 0; $i < sizeof($m); $i++) {
            $a['alt'][$i] = $m[$i]['alternativa'];
        }

        return $a;
    }

    public function getPreguntaID_PA($id) {
        $p = $this->consulta("SELECT * FROM 37978_pregunta WHERE estado = 1 AND id=$id ORDER BY id ASC");

        $idpreg = $p[0]['id'];
        $a = array();
        $a['titulo'] = $p[0]['titulo'];

        return $a;
    }

    public function getAvance() {
        $iduser = $_SESSION['id_user'];
        $p = $this->consulta("SELECT * FROM 37978_avance WHERE idusuario = $iduser");
        if (sizeof($p) < 1)
            return 0;
        return (sizeof($p) - 1);
    }

    public function registrarAvance($idpreg, $val) {
        $iduser = $_SESSION['id_user'];

        $p = $this->consulta("SELECT * FROM 37978_avance WHERE idusuario = $iduser AND idpregunta=$idpreg");
        if (sizeof($p) > 0)
            $this->sentencia("UPDATE 37978_avance SET valor='$val',actualiza='" . date('Y-m-d H:i:s') . "' WHERE idusuario = $iduser AND idpregunta=$idpreg");
        else
            $this->sentencia("INSERT INTO 37978_avance(idusuario,idpregunta,valor,fecha) VALUES ($iduser,$idpreg,'$val','" . date('Y-m-d H:i:s') . "')");

        $y = $this->consulta("SELECT COUNT(id) as n FROM 37978_avance WHERE idusuario=$iduser")[0]['n'];

        if ($y == ($_SESSION['num_preg'] + 1))
            $this->sentencia("UPDATE 37978_avance SET valor=1,actualiza='" . date('Y-m-d H:i:s') . "' WHERE idusuario=$iduser AND idpregunta=0");
    }

    public function trun() {
        $this->sentencia("TRUNCATE TABLE 37978_avance");
    }

    public function getPagUser() {
        $iduser = $_SESSION['id_user'];
        $p = $this->consulta("SELECT MAX(idpregunta) as num FROM 37978_avance WHERE idusuario = $iduser");
        if ($p[0]['num'] == 0)
            return -1;
        return ($p[0]['num'] + 1);
    }

    public function getPreguntas() {
        $p = $this->consulta("SELECT id, titulo, tipo FROM 37978_pregunta WHERE estado = 1");
        return $p;
    }

    public function eliminarPregunta($id) {
        $this->sentencia("UPDATE 37978_pregunta SET estado=0 WHERE id = $id");
    }

    public function actualizarPreguntaMA($titulo, $col1, $col2, $col3, $col4, $col5, $col6, $alt, $idpreg) { //MATRICIAL	
        $this->sentencia("UPDATE 37978_pregunta SET titulo='$titulo' WHERE id=$idpreg");

        $idcol = $this->consulta("SELECT idcolumna as idx FROM 37978_pregunta WHERE id=$idpreg")[0]['idx'];

        $this->sentencia("UPDATE 37978_columna SET col1='$col1',col2='$col2',col3='$col3',col4='$col4',col5='$col5',col6='$col6' WHERE id=$idcol");

        $this->sentencia("UPDATE 37978_alternativa SET estado=0 WHERE idpregunta=$idpreg");
        //$this->sentencia("DELETE FROM 37978_alternativa WHERE idpregunta=$idpreg");

        foreach ($alt as $alter) {
            $this->sentencia("INSERT INTO 37978_alternativa(idpregunta,alternativa,estado) VALUES ($idpreg,'$alter',1)");
        }
    }

    public function registrarPreguntaMA($titulo, $col1, $col2, $col3, $col4, $col5, $col6, $alt) { //MATRICIAL	
        $this->sentencia("INSERT INTO 37978_columna(col1,col2,col3,col4,col5, col6) VALUES ('$col1','$col2','$col3','$col4','$col5','$col6')");

        $idcol = $this->consulta("SELECT MAX(id) as idx FROM 37978_columna")[0]['idx'];

        $this->sentencia("INSERT INTO 37978_pregunta(titulo,idcolumna,estado,tipo) VALUES ('$titulo',$idcol,1,1)");

        $idpreg = $this->consulta("SELECT MAX(id) as idx FROM 37978_pregunta")[0]['idx'];

        foreach ($alt as $alter) {
            $this->sentencia("INSERT INTO 37978_alternativa(idpregunta,alternativa,estado) VALUES ($idpreg,'$alter',1)");
        }
    }

    public function actualizarPreguntaOM($titulo, $alt, $idpreg) { //OPCION MULTIPLE	
        $this->sentencia("UPDATE 37978_pregunta SET titulo='$titulo' WHERE id=$idpreg");

//		$idcol = $this->consulta("SELECT idcolumna as idx FROM 37978_pregunta WHERE id=$idpreg")[0]['idx'];	
//		
//		$this->sentencia("UPDATE 37978_columna SET col1='$col1',col2='$col2',col3='$col3',col4='$col4' WHERE id=$idcol");

        $this->sentencia("UPDATE 37978_alternativa SET estado=0 WHERE idpregunta=$idpreg");
        //$this->sentencia("DELETE FROM 37978_alternativa WHERE idpregunta=$idpreg");

        foreach ($alt as $alter) {
            $this->sentencia("INSERT INTO 37978_alternativa(idpregunta,alternativa,estado) VALUES ($idpreg,'$alter',1)");
        }
    }

    public function registrarPreguntaOM($titulo, $alt) { //OPCION MULTIPLE	
        //$this->sentencia("INSERT INTO 37978_columna(col1,col2,col3,col4) VALUES ('$col1','$col2','$col3','$col4')");
        //$idcol = $this->consulta("SELECT MAX(id) as idx FROM 37978_columna")[0]['idx'];	
        $this->sentencia("INSERT INTO 37978_pregunta(titulo,idcolumna,estado,tipo) VALUES ('$titulo','',1,2)");

        $idpreg = $this->consulta("SELECT MAX(id) as idx FROM 37978_pregunta")[0]['idx'];

        foreach ($alt as $alter) {
            $this->sentencia("INSERT INTO 37978_alternativa(idpregunta,alternativa,estado) VALUES ($idpreg,'$alter',1)");
        }
    }
  
    public function registrarPreguntaPA($titulo) { //PREGUNTA ABIERTA	
        //$this->sentencia("INSERT INTO 37978_columna(col1,col2,col3,col4) VALUES ('$col1','$col2','$col3','$col4')");
        //$idcol = $this->consulta("SELECT MAX(id) as idx FROM 37978_columna")[0]['idx'];	
        $this->sentencia("INSERT INTO 37978_pregunta(titulo, idcolumna, estado, tipo) VALUES ('$titulo','',1,3)");
    }

    public function actualizarPreguntaPA($titulo, $idpreg) { //OPCION MULTIPLE	
        $this->sentencia("UPDATE 37978_pregunta SET titulo='$titulo' WHERE id=$idpreg");

//		$idcol = $this->consulta("SELECT idcolumna as idx FROM 37978_pregunta WHERE id=$idpreg")[0]['idx'];	
//		
//		$this->sentencia("UPDATE 37978_columna SET col1='$col1',col2='$col2',col3='$col3',col4='$col4' WHERE id=$idcol");
    }

}
