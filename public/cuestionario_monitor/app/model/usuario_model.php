<?php

class usuario_model extends Model {

    public function login($user, $pass) {

        $s = $this->consulta("SELECT id , idpersona , idrol , linea FROM 37978_usuario WHERE LOWER(nombre) LIKE LOWER('" . $user . "') AND contrasenia LIKE '" . $this->md5($pass) . "' AND estado = '1'");

        if ( sizeof($s) >= 1) 
        {

            $_SESSION['rol'] = $s[0]['idrol'];

            $_SESSION['linea'] = $s[0]['linea'];

            $id = $s[0]['idpersona'];
            
            $_SESSION['id_user'] = $s[0]['id'];
            
            $s = $this->consulta("SELECT nombres, appaterno, apmaterno, idinstitucion FROM 37978_persona WHERE id LIKE '" . $id . "'");
            $_SESSION['nombre'] = ucwords(strtolower($s[0]['nombres'])) . ' ' . ucwords(strtolower($s[0]['appaterno'])) . ' ' . ucwords(strtolower($s[0]['apmaterno']));

            $s = $this->consulta("SELECT nombre, idsede FROM 37978_institucion WHERE id LIKE '" . $s[0]['idinstitucion'] . "'");
            
            $_SESSION['institucion'] = $s[0]['nombre'];

            $s = $this->consulta("SELECT nombre FROM 37978_sede WHERE id LIKE '" . $s[0]['idsede'] . "'");
            
            $_SESSION['sede'] = $s[0]['nombre'];

            $_SESSION['user'] = $user;
            
            return true;

        } else
            return false;
    }

    public function validar($usuario) {

        $s = @$this->consulta("SELECT * FROM usuario WHERE nombre LIKE '" . $usuario . "' and  estado = 'ACT'");

        if (sizeof($s) >= 1) {

            return true;
        } else
            return false;
    }

    public function getUsuarios() {

        $s = @$this->consulta("SELECT 37978_usuario.id,37978_usuario.nombre,37978_usuario.idrol, CONCAT(37978_persona.nombres,'-',37978_persona.appaterno,'-',37978_persona.apmaterno) as completo FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id WHERE 37978_persona.idinstitucion=0 AND 37978_usuario.estado=1 AND 37978_usuario.idrol=1 ORDER BY completo ASC");
        $ss = array();
        $i = 0;
        foreach ($s as $v) {
            $a = explode('-', $v['completo']);
            $ss[$i]['user'] = $v['nombre'];
            $ss[$i]['rol'] = ($v['idrol'] == 1) ? 'Administr...' : 'Director';
            $ss[$i]['nombres'] = $a[0];
            $ss[$i]['paterno'] = $a[1];
            $ss[$i]['materno'] = $a[2];
            $ss[$i]['id'] = $v['id'];
            $i++;
        }
        return $ss;
    }

    public function getUsuariosID($id) {

        $s = @$this->consulta("SELECT 37978_usuario.id,37978_usuario.nombre,37978_usuario.idrol, CONCAT(37978_persona.nombres,'-',37978_persona.appaterno,'-',37978_persona.apmaterno) as completo FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id WHERE 37978_persona.idinstitucion=$id AND 37978_usuario.estado=1 AND 37978_usuario.idrol<>2 ORDER BY completo ASC");
        $ss = array();
        $i = 0;
        foreach ($s as $v) {
            $a = explode('-', $v['completo']);
            $ss[$i]['user'] = $v['nombre'];
            $ss[$i]['rol'] = ($v['idrol'] == 1) ? 'Administr...' : 'Director';
            $ss[$i]['nombres'] = $a[0];
            $ss[$i]['paterno'] = $a[1];
            $ss[$i]['materno'] = $a[2];
            $ss[$i]['id'] = $v['id'];
            $i++;
        }
        return $ss;
    }

    public function getUsuariosSupervisores() {

        $s = @$this->consulta("SELECT 37978_usuario.id,37978_usuario.nombre,37978_usuario.idrol, CONCAT(37978_persona.nombres,'-',37978_persona.appaterno,'-',37978_persona.apmaterno) as completo FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id WHERE 37978_usuario.idrol=2 AND 37978_usuario.estado=1 ORDER BY completo ASC");
        $ss = array();
        $i = 0;
        foreach ($s as $v) {
            $a = explode('-', $v['completo']);
            $ss[$i]['user'] = $v['nombre'];
            $ss[$i]['rol'] = ($v['idrol'] == 2) ? 'Supervisor' : 'Director';
            $ss[$i]['nombres'] = $a[0];
            $ss[$i]['paterno'] = $a[1];
            $ss[$i]['materno'] = $a[2];
            $ss[$i]['id'] = $v['id'];
            $i++;
        }
        return $ss;
    }

    public function getUser($id) {
        $i = $this->consulta("SELECT idinstitucion FROM 37978_persona WHERE estado = 1 AND id=(SELECT idpersona FROM 37978_usuario WHERE id=$id)")[0]['idinstitucion'];
        if ($i == 0)
            $s = $this->consulta("SELECT id,nombre, idrol, idsede, (SELECT CONCAT(nombres,'-',appaterno,'-',apmaterno,'-0-0') FROM 37978_persona WHERE id = 37978_usuario.idpersona) as completo FROM 37978_usuario WHERE estado = 1 AND id=$id")[0];
        else
            $s = $this->consulta("SELECT id,nombre, idrol,idsede, (SELECT CONCAT(nombres,'-',appaterno,'-',apmaterno,'-',idinstitucion,'-',(SELECT id FROM 37978_sede WHERE id=(SELECT idsede FROM 37978_institucion WHERE id=37978_persona.idinstitucion))) FROM 37978_persona WHERE id = 37978_usuario.idpersona) as completo FROM 37978_usuario WHERE estado = 1 AND id=$id")[0];
        return $s;
    }

    public function registrarSedesSupervisor($dni, $nombres, $paterno, $materno, $pass, $sedes) {
        $this->sentencia("INSERT INTO 37978_persona (nombres,appaterno,apmaterno,dni,idinstitucion,estado) VALUES ('$nombres','$paterno','$materno','$dni',0,1)");
        $idpersona = $this->consulta("SELECT id FROM 37978_persona WHERE dni LIKE '$dni' AND nombres LIKE '$nombres' AND appaterno LIKE '$paterno' AND apmaterno LIKE '$materno'")[0]['id'];
        $this->sentencia("INSERT INTO 37978_usuario (idpersona,idrol,nombre,contrasenia,idsede,estado) VALUES ($idpersona,2,'$dni','" . $this->md5($pass) . "',0,1)");
        $idusuario = $this->consulta("SELECT id FROM 37978_usuario WHERE idpersona LIKE $idpersona")[0]['id'];
        foreach ($sedes as $idsede) {
            $this->sentencia("INSERT INTO 37978_sede_usuario (idsede,idusuario) VALUES ($idsede,$idusuario)");
        }
    }
                                        
    public function registrarUsuarios($dni, $nombres, $paterno, $materno, $tipo, $ie, $pass, $sede, $grado, $seccion, $semana) {
        $this->sentencia("INSERT INTO 37978_persona (nombres,appaterno,apmaterno,dni,idinstitucion, grado, seccion, semana, estado) VALUES ('$nombres','$paterno','$materno','$dni',$ie,$grado,$seccion,$semana,1)");
        $idpersona = $this->consulta("SELECT id FROM 37978_persona WHERE dni LIKE '$dni' AND nombres LIKE '$nombres' AND appaterno LIKE '$paterno' AND apmaterno LIKE '$materno'")[0]['id'];
        $this->sentencia("INSERT INTO 37978_usuario (idpersona,idrol,nombre,contrasenia,idsede,estado) VALUES ($idpersona,$tipo,'$dni','" . $this->md5($pass) . "',$sede,1)");
    }
    public function actualizarUsuSupervisor($dni, $nombres, $paterno, $materno, $id, $sedes) {
        $this->sentencia("DELETE FROM 37978_sede_usuario WHERE idusuario=$id");
        foreach ($sedes as $idsede) {
            $this->sentencia("INSERT INTO 37978_sede_usuario (idsede,idusuario) VALUES ($idsede,$id)");
        }
        $this->sentencia("UPDATE 37978_usuario SET nombre='$dni' WHERE id=$id");
        $idpersona = $this->consulta("SELECT idpersona FROM 37978_usuario WHERE id=$id")[0]['idpersona'];
        $this->sentencia("UPDATE 37978_persona SET nombres='$nombres',appaterno='$paterno',apmaterno='$materno',dni='$dni' WHERE id=$idpersona");
    }
    public function actualizarUsuario($dni, $nombres, $paterno, $materno, $ie, $id, $sede) {
        $this->sentencia("UPDATE 37978_usuario SET nombre='$dni',idsede=$sede WHERE id=$id");
        $idpersona = $this->consulta("SELECT idpersona FROM 37978_usuario WHERE id=$id")[0]['idpersona'];
        $this->sentencia("UPDATE 37978_persona SET nombres='$nombres',appaterno='$paterno',apmaterno='$materno',dni='$dni',idinstitucion=$ie WHERE id=$idpersona");
    }

    public function getSedes() {
        $s = $this->consulta("SELECT * FROM 37978_sede WHERE estado=1 ORDER BY sedeid ASC");
        return $s;
    }

    public function getSede($id) {
        $s = $this->consulta("SELECT * FROM 37978_sede WHERE estado=1 AND id=(SELECT idsede FROM 37978_usuario WHERE id=$id) ORDER BY sedeid ASC")[0];
        return $s;
    }

    public function getSedes_usuario($id) {
//            su.idsede, se.sedeid, se.nombre

        $s = $this->consulta("SELECT * from 37978_sede_usuario as su inner join 37978_sede as se on se.id=su.idsede WHERE su.idusuario=$id and se.estado=1");
        return $s;
    }

    public function getSedes_usuario2($id) {
//            su.idsede, se.sedeid, se.nombre
        $s = $this->consulta("SELECT idsede from 37978_sede_usuario as su inner join 37978_sede as se on se.id=su.idsede WHERE su.idusuario=$id and se.estado=1");
        return $s;
    }

    public function getIes($id) {
        $s = $this->consulta("SELECT * FROM 37978_institucion WHERE estado=1 AND idsede = $id ORDER BY nombre ASC");
        return $s;
    }

    public function deleteUser($id) {
        $this->sentencia("UPDATE 37978_usuario SET estado=0 WHERE estado=1 AND id = $id");
    }

    public function cambiaPass($p1, $p2) {
        $user = $_SESSION['id_user'];
        $s = $this->sentencia("UPDATE 37978_usuario SET contrasenia='" . $this->md5($p2) . "'  WHERE id=$user AND contrasenia LIKE '" . $this->md5($p1) . "'");
        return $s;
    }

    public function validPass($p1) {
        $user = $_SESSION['id_user'];
        $s = $this->consulta("SELECT id FROM 37978_usuario WHERE id=$user AND contrasenia LIKE '" . $this->md5($p1) . "' AND estado = '1'");
        if (sizeof($s) > 0)
            return '1';
        return '0';
    }

    public function existeUser($dni) {
        $s = $this->consulta("SELECT COUNT(id) as n FROM 37978_usuario WHERE nombre='" . $dni . "' and estado=1")[0]['n'];
        if ($s == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function existeUserID($id, $dni) {
        $s = $this->consulta("SELECT COUNT(id) as n FROM 37978_usuario WHERE nombre='" . $dni . "' AND estado=1 AND id<>'" . $id . "'")[0]['n'];
        if ($s == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function guardarExcel($g) {
        for ($i = 0; $i < sizeof($g); $i++) {
            $g[$i]['estado'] = 0;
            $s = $this->consulta("SELECT COUNT(id) as n FROM 37978_sede WHERE sedeid LIKE '" . $g[$i]['id'] . "' AND nombre LIKE '" . $g[$i]['sede'] . "' AND estado=1 ")[0]['n'];
            if ($s == 0) {
                $this->sentencia("INSERT INTO 37978_sede (sedeid,nombre,estado) VALUES ('" . $g[$i]['id'] . "','" . $g[$i]['sede'] . "',1)");
            }

            $s = $this->consulta("SELECT COUNT(id) as n FROM 37978_institucion WHERE modular LIKE '" . $g[$i]['modular'] . "' AND estado=1 ")[0]['n'];
            if ($s == 0) {
                $idsede = $this->consulta("SELECT id FROM 37978_sede WHERE sedeid LIKE '" . $g[$i]['id'] . "' AND nombre LIKE '" . $g[$i]['sede'] . "' AND estado=1 ")[0]['id'];

                $this->sentencia("INSERT INTO 37978_institucion (modular,nombre,idsede,estado) VALUES ('" . $g[$i]['modular'] . "','" . $g[$i]['ie'] . "'," . $idsede . ",1)");
            }

            $nombres = $g[$i]['nombres'];
            $paterno = $g[$i]['paterno'];
            $materno = $g[$i]['materno'];
            $dni = $g[$i]['usuario'];
            $grado = $g[$i]['grado'];
            $seccion = $g[$i]['seccion'];
            $semana = $g[$i]['semana'];
            $s = $this->consulta("SELECT COUNT(id) as n FROM 37978_persona WHERE dni LIKE '" . $g[$i]['usuario'] . "' AND estado=1 ")[0]['n'];
            if ($s == 0) {
                $ie = $this->consulta("SELECT id FROM 37978_institucion WHERE modular LIKE '" . $g[$i]['modular'] . "' AND estado=1 ")[0]['id'];
                $this->sentencia("INSERT INTO 37978_persona (nombres,appaterno,apmaterno,dni,idinstitucion,estado,grado,seccion,semana) VALUES ('$nombres','$paterno','$materno','$dni',$ie,1,'$grado','$seccion','$semana')");
            }

            $s = $this->consulta("SELECT COUNT(id) as n FROM 37978_usuario WHERE nombre LIKE '" . $g[$i]['usuario'] . "' AND estado=1 ")[0]['n'];
            if ($s == 0) {
                $id = $this->consulta("SELECT id FROM 37978_persona WHERE dni LIKE '$dni' AND estado=1")[0]['id'];
                $this->sentencia("INSERT INTO 37978_usuario (idpersona,idrol,nombre,contrasenia,estado) VALUES ($id,3,'$dni','" . $this->md5($dni = $g[$i]['clave']) . "',1)");
                $g[$i]['estado'] = 1;
            }
        }
        return $g;
    }

}
