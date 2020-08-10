<?php

class reportes_model extends Model {

    public function getCantPreg() {
        $p = $this->consulta("SELECT count(id) as count FROM 37978_pregunta WHERE estado = 1");
        return $p[0]['count'];
    }

    public function toAlphabet($alt) {
        $alphabet = array('A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'Ñ', 'O',
            'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y',
            'Z'
        );

        $length = count($alphabet);
        for ($i = 0; $i < $length; $i++) {
            if ($i === $alt - 1) {
                $return = $alphabet[$i];
            }
        }
        return $return;
    }

    public function getTotalUsuarios($ids, $idi) {
        if ($ids == 0)//TODAS LAS SEDES
            return $this->consulta("SELECT COUNT(id) as n FROM 37978_usuario WHERE estado=1 AND idrol=3")[0]['n'];

        if ($idi == 0)//TODAS LAS  IE
            // return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion  
            // inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
            // WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND pe.Est_EstadoSeleccion= 1  AND 37978_institucion.idsede = $ids")[0]['n'];

        return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion  

            WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_institucion.idsede = $ids")[0]['n'];

        // return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id 
        // inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
        // WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND pe.Est_EstadoSeleccion= 1 AND 37978_persona.idinstitucion=$idi")[0]['n'];

        return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id 

        WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_persona.idinstitucion=$idi")[0]['n'];

    }

    public function getTotalCompletados($n, $ids, $idi) {
        if ($ids == 0)//TODAS LAS SEDES
            // return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  
            // inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
            // WHERE 37978_usuario.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n")[0]['n'];

            return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  

            WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n")[0]['n'];

        if ($idi == 0)//TODAS LAS  IE
   //          return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  
			// inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
			// WHERE 37978_usuario.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n AND 37978_institucion.idsede=$ids")[0]['n'];

            return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  
            WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n AND 37978_institucion.idsede=$ids")[0]['n'];

  //       return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id  INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  
		// inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
		// WHERE 37978_usuario.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n AND 37978_persona.idinstitucion=$idi")[0]['n'];

            return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id  INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  
        WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n AND 37978_persona.idinstitucion=$idi")[0]['n'];

    }

    public function getUsuarioEstudiantes($ids, $idi) {
        if ($ids == 0)
   //          return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular, 37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 
			// inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
			// WHERE 37978_usuario.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_usuario.idrol=3 ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");

            return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular, 37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 
            WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");

        if ($idi == 0)
   //          return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular,37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana  FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 
			// inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
			// WHERE 37978_usuario.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_usuario.idrol=3 AND 37978_sede.id=$ids ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");

            return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular,37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana  FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 
            WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_sede.id=$ids ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");


        // return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular,37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana  FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 
        // inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
        // WHERE 37978_usuario.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_usuario.idrol=3 AND 37978_institucion.id=$idi ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");

        return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular,37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana  FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 

        WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_institucion.id=$idi ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");

    }

    public function getPregunta() {
  //       return $this->consulta("SELECT 37978_pregunta.tipo as tipo, 37978_pregunta.titulo as titulo,37978_columna.col1 as col1,37978_columna.col2 as col2,37978_columna.col3 as col3,37978_columna.col4 as col4, 37978_columna.col5 as col5, 37978_alternativa.alternativa as alternativa  FROM 37978_alternativa INNER JOIN 37978_pregunta ON 37978_pregunta.id = 37978_alternativa.idpregunta LEFT JOIN 37978_columna ON 37978_columna.id = 37978_pregunta.idcolumna 
		// inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di
		// WHERE 37978_pregunta.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_alternativa.estado=1 ORDER BY 37978_pregunta.id,37978_alternativa.id,37978_columna.id ASC");

        return $this->consulta("SELECT 37978_pregunta.tipo as tipo, 37978_pregunta.titulo as titulo,37978_columna.col1 as col1,37978_columna.col2 as col2,37978_columna.col3 as col3,37978_columna.col4 as col4, 37978_columna.col5 as col5, 37978_alternativa.alternativa as alternativa  FROM 37978_alternativa INNER JOIN 37978_pregunta ON 37978_pregunta.id = 37978_alternativa.idpregunta LEFT JOIN 37978_columna ON 37978_columna.id = 37978_pregunta.idcolumna 

        WHERE 37978_pregunta.estado=1 AND 37978_alternativa.estado=1 ORDER BY 37978_pregunta.id,37978_alternativa.id,37978_columna.id ASC");

    }

    public function getResultados($id) {
        return $this->consulta("SELECT valor FROM 37978_avance WHERE idusuario = $id AND idpregunta <> 0 ORDER BY idpregunta");
    }

    public function getEstadoID($id) {
        $a = $this->consulta("SELECT MAX(valor) as n FROM 37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n'];
        if ($a == NULL) {
            $r['estado'] = 'No ingresó';
            $r['ini'] = ' ';
            $r['fin'] = ' ';
            $r['tiempo'] = ' ';
            $r['avance'] = '0 de ' . $_SESSION['num_preg'];
            return $r;
        } else if ($a == 0) {
            $r['estado'] = 'Pendiente';
            $r['ini'] = date_create($this->consulta("SELECT fecha as n FROM 37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n']);
            $r['ini'] = date_format($r['ini'], 'd/m/Y | H:i');
            $r['fin'] = ' ';
            $r['tiempo'] = ' ';
            $d = $this->consulta("SELECT COUNT(id) as n FROM 37978_avance WHERE idpregunta<>0 AND idusuario=$id")[0]['n'];
            $r['avance'] = $d . ' de ' . $_SESSION['num_preg'];
            return $r;
        } else if ($a == 1) {
            $r['estado'] = 'Finalizado';
            $r['ini'] = date_create($this->consulta("SELECT fecha as n FROM 37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n']);
            $r['fin'] = date_create($this->consulta("SELECT actualiza as n FROM 37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n']);

            $r['tiempo'] = date_diff($r['ini'], $r['fin']);
            $r['tiempo'] = $r['tiempo']->format('%a días y %H:%I:%S');

            $r['ini'] = date_format($r['ini'], 'd/m/Y | H:i');
            $r['fin'] = date_format($r['fin'], 'd/m/Y | H:i');

            $r['avance'] = $_SESSION['num_preg'] . ' de ' . $_SESSION['num_preg'];
            return $r;
        }
    }

}
