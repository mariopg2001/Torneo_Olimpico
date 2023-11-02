<?php
require_once '../Modelo/modeloPrueba.php';
    
    class ControladorPrueba{
    
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new ModeloPrueba();
        }
        public function responsable(){
            $resultado=$this->modelo->responsable();
            return $resultado;
        }
        public function InsertarPrueba( $participantes,$finicio,$Ffin,$responsable,$nombrePrueba){
            $resultado=$this->modelo->InsertarPrueba( $participantes,$finicio,$Ffin,$responsable,$nombrePrueba);
            return $resultado;
        }
        public function Prueba(){
            $resultado=$this->modelo->Prueba();
            return $resultado;
        }
        public function modificar($datos){
           
            $resultado=$this->modelo->modificar($datos);
        
            if($resultado=='ok'){
                header('location: ../index.php ');
                exit;
            }
        }
        public function eliminar($idPrueba){
            $resultado=$this->modelo->borrar($idPrueba);
            if($resultado>0){
                header('location: ../index.php ');
                exit;
            }        
        }
    }