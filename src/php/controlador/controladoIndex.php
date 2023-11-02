<?php
require_once './Modelo/modeloIndex.php';
    
    class ControladorIndex{
    
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new ModeloIndex();
        }
        public function responsable($id){
            $resultado=$this->modelo->responsable($id);
            return $resultado;
        }
       
        public function pruebas(){
            $resultado=$this->modelo->pruebas();
            return $resultado;
        }
      
    }