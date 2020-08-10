<?php

class incidentes_model extends Model {

    public function setIncidente( $datos , $tipo = 0 , $actualiza = 0 , $idincidencia = null ) 
    {

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

    	if ( $datos['cambio_fecha'] )
    	{

	        $this->consulta("UPDATE " . $bd ."37978_persona SET 
		        							fecha_participacion = STR_TO_DATE('" . $datos['cambio_fecha'] ."','%d/%m/%Y')
	        							WHERE 
	        								id =". $datos['id']);
    	}

        if ( $actualiza == "0" )
        {

            return $this->insert("INSERT INTO " . $bd ."37978_incidente (idpersona, problema, observaciones, acciontomada, solucionado, linea02, otra_fecha, idregistrador) VALUES ('$datos[id]','$datos[motivo]','$datos[observaciones]','$datos[accion]',$datos[solucion],$datos[linea02],STR_TO_DATE('$datos[cambio_fecha]','%d/%m/%Y') , $_SESSION[id_user] )");
        
        }
        else
        {

            return $this->insert("UPDATE " . $bd ."37978_incidente SET 
                                                    idpersona = '$datos[id]', 
                                                    problema = '$datos[motivo]', 
                                                    observaciones = '$datos[observaciones]', 
                                                    acciontomada = '$datos[accion]', 
                                                    solucionado = $datos[solucion], 
                                                    linea02 = $datos[linea02], 
                                                    otra_fecha = STR_TO_DATE('$datos[cambio_fecha]','%d/%m/%Y'), 
                                                    idregistrador = $_SESSION[id_user] 
                                            WHERE 
                                            id = $idincidencia" );


        }

    }

    public function setAtencionIncidente( $datos , $tipo = 0 ) 
    {

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

        return $this->consulta("UPDATE " . $bd ."37978_incidente SET 
	        							observaciones_l2 = '$datos[observaciones]', 
	        							acciontomada_l2 = '$datos[accion]', 
	        							solucionado_l2 = $datos[solucion],
	        							estado = " . ( $datos['solucion'] == "1" ? 1 : 0 ) . "
        							WHERE 
        								id = '$datos[id]'");


    }

    public function getHistoria($idpersona , $tipo = 0) {

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

        return $this->consulta("SELECT 

        							problema,
        							observaciones,
        							acciontomada,
        							solucionado,
        							solucionado_l2,
        							linea02,
        							DATE_FORMAT(otra_fecha, '%d/%m/%Y') cambio_fecha,
        							fecha_hora, 
        							idpersona,
        							id incidente, 
                                    idregistrador

         						FROM  " . $bd ."37978_incidente

                                    WHERE idpersona=$idpersona 

                                    ORDER BY id ASC");


    }

    public function getConsultaHistoria($idincidente , $tipo = 0 ) 
    {
        
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

        return $this->consulta("SELECT 

        							problema,
        							observaciones,
        							acciontomada,
        							solucionado,
        							linea02,
        							DATE_FORMAT(otra_fecha, '%d/%m/%Y') cambio_fecha,
        							fecha_hora,
									observaciones_l2,
									acciontomada_l2,
									solucionado_l2,
        							id incidente


         						FROM  " . $bd ."37978_incidente

                                    WHERE id= $idincidente");


    }


}
