<?php

class Router {

    static function main() {
        require 'libs/kurmi/controller.php';
        require 'libs/kurmi/model.php';

        $controlador = 'index';
        $accion = 'index';

        if (!empty($_GET['k'])) {
            $a = explode('/', $_GET['k']);

            if (sizeof($a) > 0)
                $controlador = $a[0];

            if (sizeof($a) > 1)
                $accion = $a[1];
        }

        if (!file_exists('app/controller/' . $controlador . '_controller.php'))
            die('No exite el controlador: ' . $controlador);

        if (!Controller::verificar_sesion() && ($controlador != 'index' || $accion != 'validar')) {
            $controlador = 'index';
            $accion = 'login';
        }

// echo $accion;return;		
        require ('app/controller/' . $controlador . '_controller.php');
        $controlador_class = $controlador . '_controller';
        $controler = new $controlador_class();

        if (!method_exists($controler, $accion))
            die('No existe la accion: ' . $accion . ' en el controlador: ' . $controlador);

        $rf = new ReflectionMethod($controlador_class, $accion);
        $n = $rf->getNumberOfParameters();

        $params = array();
        $j = 2;
        for ($i = 0; $i < $n; $i++) {
            $params[$i] = ($j < sizeof($a)) ? $a[$j] : "";
            $j++;
        }
        
        $controler->pre_accion();
        call_user_func_array(array($controler, $accion), $params);
        $controler->pos_accion($controlador . '/' . $accion);
    }

}
