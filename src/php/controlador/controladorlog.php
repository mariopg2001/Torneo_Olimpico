<?php 
    require_once '../modelo/modelolog.php';
    
    class Controladorlog{
    
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new Modelolog();
        }

        public function iniciarSesion($correo){
           $result = $this->modelo->iniciarSesion($correo);
           if($result[0]>0){
                session_start();
              
                $_SESSION['usuario'] = $result[0];
                $_SESSION['tipoUsuario'] = $result[1];
                echo'<meta http-equiv="refresh" content="0;url=../index.php">';
                
            }    
        }
    }