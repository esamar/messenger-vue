<?php

class View {

    protected $view;
    protected $template;
    protected $data;

    function __construct() {
        $this->template = true;
        $this->data = array();
    }

    public function mostrar() {
        if ($this->view == null)
            return;
        $path = "app/view/" . $this->view . ".html";
        if (!file_exists($path))
            die('No exite la vista: ' . $path);

        $data = $this->data;

        $a = explode('/', $this->view);
        $pagina = $a[0];

        if ($this->template)
            include("app/view/_template/header.html");

        include($path); //INCLUYE VIEW HTML

        if ($this->template)
            include("app/view/_template/footer.html");
    }

    public function setView($view) {
        $this->view = $view;
    }

    public function setTemplate($view) {
        $this->template = $view;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function mostrar223($datos) {
        header('Access-Control-Allow-Origin: *');
        if ($this->view == null) {
            return;
        }
        $kur['view'] = $this->view;
        echo json_encode(array('kur' => $kur, 'datos' => $datos));
    }

    public function mostrar22($name, $vars = array(), $template = true) {

        $path = "app/vista/" . $name . ".html";
        if (!file_exists($path)) {
            die('No exite la vista: ' . $name);
        }

        /*
          //Si hay variables para asignar, las pasamos una a una.
          if(is_array($vars))
          {
          foreach ($vars as $key => $value)
          {
          $$key = $value;
          }
          }

          //Finalmente, incluimos la plantilla.
         */
        if ($template)
            include("app/vista/template/header.html");

        include($path);

        if ($template)
            include("app/vista/template/footer.html");
    }

}
