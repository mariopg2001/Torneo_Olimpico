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
        public function responsable2($id){
            $resultado=$this->modelo->responsable2($id);
            return $resultado;
        }
        public function InsertarPrueba( $participantes,$responsable,$nombrePrueba){
            $resultado=$this->modelo->InsertarPrueba( $participantes,$responsable,$nombrePrueba);
            return $resultado;
        }
        public function Prueba(){
            $resultado=$this->modelo->Prueba();
            return $resultado;
        }
        public function modificar($datos){
           
            $resultado=$this->modelo->modificar($datos);
        
            if($resultado=='ok'){
                header('location: ./indexPrueba.php ');
                exit;
            }
        }
        public function eliminar($idPrueba){
            $resultado=$this->modelo->borrar($idPrueba);
            if($resultado>0){
                header('location: ./indexPrueba.php ');
                exit;
            }        
        }
        public function pruebas(){
            $resultado=$this->modelo->pruebas();
            return $resultado;
        }
    }