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
   //          return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion  
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

        // return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id  INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  
        // inner join admin_bd2018_pisa.tb_estudiante pe on 37978_persona.dni = pe.Est_Di 
        // WHERE 37978_usuario.estado=1 AND pe.Est_EstadoSeleccion= 1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n AND 37978_persona.idinstitucion=$idi")[0]['n'];


        return $this->consulta("SELECT COUNT(37978_usuario.id) as n FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id  INNER JOIN 37978_avance ON 37978_avance.idusuario = 37978_usuario.id  

        WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_avance.idpregunta=0 AND 37978_avance.valor=$n AND 37978_persona.idinstitucion=$idi")[0]['n'];
    }

    public function getUsuarioEstudiantes($ids, $idi , $tipo = 0) {

        switch ( $tipo ) 
        {
            
            case 0:
                $bd = '';
                break;
            
            case 1:
                $bd = 'cuestionario_v01.';
                break;
            
            case 2:
                $bd = 'admin_bd2020_cuestionario_docente.';
                break;

            case 3:
                $bd = 'admin_bd2020_cuestionario_ppff.';
                break;
            
            case 4:
                $bd = 'admin_bd2020_cuestionario_estudiante.';
                break;
        
        }

        if ($ids == 0)
        {
            
            $filtro = "";

        }
        elseif ( $idi == 0 )
        {

            $filtro = " AND D.id=$ids ";

        }
        else
        {

            $filtro = " AND C.id=$idi ";

        }


        if ( $_SESSION['linea'] == "1" )
        {

            $consulta = "SELECT 
                            A.id as id, 
                            A.nombre as user,
                            B.nombres as nombre ,
                            B.appaterno as paterno,
                            B.apmaterno as materno ,
                            CONCAT(D.sedeid,' - ',D.nombre) as sede,
                            C.modular as modular, 
                            C.nombre as ie,
                            B.grado as grado,
                            B.seccion as seccion,
                            B.semana as semana, 
                            DATE_FORMAT(B.fecha_participacion, '%d/%m/%Y') as fecha_participacion ,
                            A.idpersona

                        FROM " . $bd . "37978_usuario A 
                        JOIN " . $bd . "37978_persona B ON ( A.idpersona=B.id )
                        JOIN 37978_institucion C ON ( C.id = B.idinstitucion )
                        JOIN 37978_sede D ON ( D.id = C.idsede ) 
                        WHERE A.estado=1 AND A.idrol=3 " . $filtro . " 
                        ORDER BY B.appaterno,B.apmaterno,B.nombres ASC";
        
        }
        else if ( $_SESSION['linea'] == "2" )
        {

            $consulta = "SELECT 
                            A.id as id, 
                            A.idpersona,
                            A.nombre as user,
                            B.nombres as nombre ,
                            B.appaterno as paterno,
                            B.apmaterno as materno ,
                            CONCAT(D.sedeid,' - ',D.nombre) as sede,
                            C.modular as modular, 
                            C.nombre as ie,
                            DATE_FORMAT( B.fecha_participacion, '%d/%m/%Y') as fecha_participacion,
                            E.id incidente,
                            linea02 linea_atencion
                        FROM " . $bd . "37978_usuario A 
                        JOIN " . $bd . "37978_persona B ON ( A.idpersona=B.id )
                        JOIN 37978_institucion C ON ( C.id = B.idinstitucion )
                        JOIN 37978_sede D ON ( D.id = C.idsede ) 
                        LEFT JOIN  " . $bd . "37978_incidente E ON ( A.idpersona = E.idpersona AND E.estado = 0)
                        WHERE A.estado=1 AND A.idrol=3 " . $filtro . " 
                        GROUP BY A.idpersona
                        ORDER BY B.appaterno,B.apmaterno,B.nombres ASC ";

        }

        // echo  $consulta ;
        return $this->consulta( $consulta );
        
        // if ($idi == 0)
        //  {

        //     return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular,37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana  FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 

        //     WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_sede.id=$ids ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");
        // }

        // return $this->consulta("SELECT 37978_usuario.id as id, 37978_usuario.nombre as user,37978_persona.nombres as nombre ,37978_persona.appaterno as paterno,37978_persona.apmaterno as materno ,CONCAT(37978_sede.sedeid,' - ',37978_sede.nombre) as sede,37978_institucion.modular as modular,37978_institucion.nombre as ie,37978_persona.grado as grado,37978_persona.seccion as seccion,37978_persona.semana as semana  FROM 37978_usuario INNER JOIN 37978_persona ON 37978_usuario.idpersona=37978_persona.id INNER JOIN 37978_institucion ON 37978_institucion.id = 37978_persona.idinstitucion INNER JOIN 37978_sede ON 37978_sede.id = 37978_institucion.idsede 

        // WHERE 37978_usuario.estado=1 AND 37978_usuario.idrol=3 AND 37978_institucion.id=$idi ORDER BY 37978_persona.appaterno,37978_persona.apmaterno,37978_persona.nombres ASC");


    }

    public function getReporteIncidentes($ids, $idi , $tipo = 0) 
    {

        switch ( $tipo ) 
        {
            
            case 0:
                $bd = '';
                break;
            
            case 1:
                $bd = 'cuestionario_v01.';
                $rol = 'Director';
                break;
            
            case 2:
                $bd = 'admin_bd2020_cuestionario_docente.';
                $rol = 'Docente';
                break;

            case 3:
                $bd = 'admin_bd2020_cuestionario_ppff.';
                $rol = 'PPFF';
                break;
            
            case 4:
                $bd = 'admin_bd2020_cuestionario_estudiante.';
                $rol = 'Estudiante';
                break;
        
        }

        if ($ids == 0)
        {
            
            $filtro = "";

        }
        elseif ( $idi == 0 )
        {

            $filtro = " AND D.id=$ids ";

        }
        else
        {

            $filtro = " AND C.id=$idi ";

        }

        $consulta = "SELECT 
                        A.id, 
                        region,
                        provincia,
                        dre,
                        ugel,
                        C.modular, 
                        C.nombre,
                        '$rol' rol,
                        B.nombres, 
                        B.appaterno, 
                        B.apmaterno, 
                        CONCAT( grado , '.º ' , seccion) grado_seccion,
                        telefono,
                        DATE_FORMAT(fecha_participacion, '%d/%m/%Y') fecha_participacion
                        'Linea 1' linea_1,
                        observaciones,
                        problema,
                        acciontomada,
                        CASE WHEN solucionado = 1 THEN 
                            'Si' 
                        ELSE
                            CASE WHEN  solucionado = 2 THEN 
                                'No'
                            END
                        END solucionado_l1,
                        CASE WHEN NOT ISNULL(observaciones_l2) THEN  'Linea 2' END linea_2,
                        observaciones_l2,
                        acciontomada_l2,
                         CASE WHEN solucionado_l2 = 1 THEN 
                            'Si' 
                        ELSE
                            CASE WHEN  solucionado_l2 = 2 THEN 
                                'No'
                            END
                        END solucionado_l2,
                        sedeid codigo_monitor
                    FROM " . $bd . "37978_usuario A 
                    JOIN " . $bd . "37978_persona B ON ( A.idpersona=B.id )
                    JOIN 37978_institucion C ON ( C.id = B.idinstitucion )
                    JOIN 37978_sede D ON ( D.id = C.idsede ) 
                    LEFT JOIN  " . $bd . "37978_incidente E ON ( A.idpersona = E.idpersona AND E.estado = 0)
                    WHERE A.estado=1 AND A.idrol=3 " . $filtro . " 
                    ORDER BY B.appaterno,B.apmaterno,B.nombres ASC ";

        // echo  $consulta ;
        return $this->consulta( $consulta );
    
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

    public function getEstadoID($id , $tipo = 0) {

        switch ( $tipo ) 
        {
            
            case 0:
                $bd = '';
                break;
            
            case 1:
                $bd = 'cuestionario_v01.';
                break;
            
            case 2:
                $bd = 'admin_bd2020_cuestionario_docente.';
                break;

            case 3:
                $bd = 'admin_bd2020_cuestionario_ppff.';
                break;
            
            case 4:
                $bd = 'admin_bd2020_cuestionario_estudiante.';
                break;
        
        }


        $a = $this->consulta("SELECT MAX(valor) as n FROM " . $bd . "37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n'];

        $num_preg = $this->consulta("SELECT count(id) as count FROM " . $bd . "37978_pregunta WHERE estado = 1")[0]['count'];

        if ($a == NULL) {
            $r['estado'] = 'No ingresó';
            $r['ini'] = ' ';
            $r['fin'] = ' ';
            $r['tiempo'] = ' ';
            // $r['avance'] = '0 de ' . $_SESSION['num_preg'];
            $r['avance'] = '0 de ' . $num_preg;
            return $r;
        } else if ($a == 0) {
            $r['estado'] = 'Pendiente';
            $r['ini'] = date_create($this->consulta("SELECT fecha as n FROM " . $bd . "37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n']);
            $r['ini'] = date_format($r['ini'], 'd/m/Y | H:i');
            $r['fin'] = ' ';
            $r['tiempo'] = ' ';
            $d = $this->consulta("SELECT COUNT(id) as n FROM " . $bd . "37978_avance WHERE idpregunta<>0 AND idusuario=$id")[0]['n'];
            $r['avance'] = $d . ' de ' . $num_preg;
            // $r['avance'] = $d . ' de ' . $_SESSION['num_preg'];
            return $r;
        } else if ($a == 1) {
            $r['estado'] = 'Finalizado';
            $r['ini'] = date_create($this->consulta("SELECT fecha as n FROM " . $bd . "37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n']);
            $r['fin'] = date_create($this->consulta("SELECT actualiza as n FROM " . $bd . "37978_avance WHERE idpregunta=0 AND idusuario=$id")[0]['n']);

            $r['tiempo'] = date_diff($r['ini'], $r['fin']);
            $r['tiempo'] = $r['tiempo']->format('%a días y %H:%I:%S');

            $r['ini'] = date_format($r['ini'], 'd/m/Y | H:i');
            $r['fin'] = date_format($r['fin'], 'd/m/Y | H:i');

            // $r['avance'] = $_SESSION['num_preg'] . ' de ' . $_SESSION['num_preg'];
            $r['avance'] = $num_preg . ' de ' . $num_preg;
            return $r;
        }
    }

    public function getEstadoExt($text) {


        $a = $this->consultaExt("222");


    }


}
