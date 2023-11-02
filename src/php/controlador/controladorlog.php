<?php 
    require_once '../modelo/modelolog.php';
    
    class Controladorlog{
    
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new Modelolog();
        }

        public function iniciarSesion($correo,$usuario ){
           $datos= $this->modelo->iniciarSesion($correo,$usuario );
           if($datos>0){
                session_start();
                $_SESSION['usuario']=$datos;
                header('Location: ../index.php');
            }    
        }
    }