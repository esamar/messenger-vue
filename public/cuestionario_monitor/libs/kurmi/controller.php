<?php

abstract class Controller {

    protected $data;
    protected $vista;
    protected $band;

    function __construct() {
        require_once 'libs/kurmi/view.php';
        $this->vista = new View();
        $this->band = true;
    }

    function pre_accion() {
        
    }

    function pos_accion($view) {
        if ($this->band) {
            $this->vista->setView($view);
        }
        $this->vista->mostrar();
    }

    function view($view, $temp = '') {
        $this->vista->setView($view);
        $this->band = false;
    }

    function template($temp) {
        if ($temp == null) {
            $temp = false;
        }
        $this->vista->setTemplate($temp);
    }

    function model($model) {
        if (!file_exists('app/model/' . $model . '_model.php')) {
            die('No exite el modelo: ' . $model);
        }
        require ('app/model/' . $model . '_model.php');
        $model = $model . '_model';
        return new $model();
    }

    function datos($datos) {
        $this->vista->setData($datos);
    }

    function index() {
        
    }

    function mostrarlll($name, $vars = array(), $template = true) {
        $this->vista->mostrar($name, $vars, $template);
    }

    public static function verificar_sesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['ini'])) {
            return false;
        } else {
            return true;
        }
    }

    function redirect($dir) {
        echo '<script>location.href="' . $dir . '";</script>';
    }

}
