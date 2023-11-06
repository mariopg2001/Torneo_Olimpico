<?php
require_once './Modelo/modeloIndex.php';
    
    class ControladorIndex{
    
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new ModeloIndex();
        }
       
       
       
      
    }