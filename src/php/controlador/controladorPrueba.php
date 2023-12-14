<?php
require_once '../modelo/modeloPrueba.php';
    
    class ControladorPrueba{
    
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new ModeloPrueba();
        }

        //funciones de IndexPrueba
        public function pruebasyfilas(){
            $resultado=$this->modelo->pruebasyfilas();
            return $resultado;
        }
        public function responsablePrueba($id){
            $resultado=$this->modelo->responsablePrueba($id);
            return $resultado;
        }

        //Funciones de FormularioPrueba

        public function responsable(){
            $resultado=$this->modelo->responsable();
            return $resultado;
        }
        public function InsertarPrueba( $participantes,$responsable,$nombrePrueba){
            $resultado=$this->modelo->InsertarPrueba( $participantes,$responsable,$nombrePrueba);
            return $resultado;
        }

        //funciones de Form_update
        
        public function pruebaDatos(){
            $resultado=$this->modelo->pruebaDatos();
            return $resultado;
        }
        public function modificar($datos){
           
            $resultado=$this->modelo->modificar($datos);
        
            if($resultado=='ok'){
                echo'<meta http-equiv="refresh" content="0;url=./indexPrueba.php">';
                
            }
        }

        //funciones de borrar

        public function eliminar($idPrueba){
            $resultado=$this->modelo->borrar($idPrueba);
            if($resultado>0){
                echo'<meta http-equiv="refresh" content="0;url=./indexPrueba.php">';
                
            }
        }
     
    }