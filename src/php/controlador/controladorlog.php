<?php 
    require_once '../modelo/modelolog.php';
    
    class Controladorlog{
    
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new Modelolog();
        }

        public function iniciarSesion($correo){
           $id = $this->modelo->iniciarSesion($correo);
           if($id>0){
                session_start();
                $_SESSION['usuario'] = $id;
                $_SESSION['edad'] = 28;
                echo'<meta http-equiv="refresh" content="0;url=./indexPrueba.php">';
                
            }    
        }
    }