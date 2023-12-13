<?php
    require_once '../modelo/modeloExcel.php';
    
    class ControladorExcel{
    
        private $modelo;   
        public function __construct(){
            $this->modelo = new ModeloExcel();
        }
        public function getPruebaExclusiva(){
            $resultado = $this->modelo->getPruebaExclusiva();
            return $resultado;
        }
        public function getPrueba4x100(){
            $resultado = $this->modelo->getPrueba4x100();
            return $resultado;
        }
        public function exportarInscripciones($pruebas){
            $resultado = $this->modelo->exportarInscripciones($pruebas);
            return $resultado;
        }
    }