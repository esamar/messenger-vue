<?php

class estado_controller extends Controller {

    function pre_accion() {
        if ($_SESSION['rol'] != 3)
            $this->redirect('?');
    }

    /* 	
      function index(){
      $p = $this->model('cuestionario');

      if(!empty($_POST['valores'])){
      $val = $_POST['valores'];
      $n=$_SESSION['num_preg']+1;
      $p->registrarAvance($n,$val);
      }


      if(empty($_SESSION['num_preg'])) $_SESSION['num_preg']=$p->getCantPreg();
      $num_preg = $_SESSION['num_preg'];
      $a['a']=$p->getAvance();
      $a['b']=$num_preg-$a['a'];

      if($a['a']==0)
      $a['mensaje']="Aun no has empezado a responder las preguntas, ¡vámos!.";
      else if($a['b']==0)
      $a['mensaje']="<strong>¡Muchas gracias por participar!</strong><br>Haz terminado de responder el cuestionario. Puedes cerrar tu sesión";
      else
      $a['mensaje']="Te falta responder mas preguntas, ¡Estas a tiempo!.";

      $this->datos($a);
      }
     */

    function salir() {
        $p = $this->model('cuestionario');

        if (empty($_SESSION['num_preg']))
            $_SESSION['num_preg'] = $p->getCantPreg();
        $num_preg = $_SESSION['num_preg']; //total de preguntas
        $a['a'] = $p->getAvance(); //preguntas respondidas
        $a['b'] = $num_preg - $a['a']; //preguntas sin responder

        if ($a['a'] == 0)
            //$a['mensaje'] = "Antes de cerrar tu sesión, haz clic en “Siguiente” para guardar tus respuestas.";
            $a['mensaje'] = "No has respondido el cuestionario. ¿Seguro que quieres salir?";
        else if ($a['b'] == 0)
            $a['mensaje'] = 'Gracias'; //"Ya terminaste el cuestonario, gracias por tomarte tu tiempo.";
        else
            //$a['mensaje'] = "Antes de cerrar tu sesión, haz clic en “Siguiente” para guardar tus respuestas.";
            $a['mensaje'] = "Tienes preguntas sin responder. ¿Seguro que quieres salir?";
        echo $a['mensaje'];
        $this->view(null);
    }

    function fin() {
        
    }

}
