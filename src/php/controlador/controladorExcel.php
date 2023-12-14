<?php
    require_once '../modelo/modeloExcel.php';
    
    class ControladorExcel{
    
        private $modelo;   
        public function __construct(){
            $this->modelo = new ModeloExcel();
        }
        // Función para obtener las pruebas exclusivas existentes en la base de datos para ser mostradas en la vista
        public function getPruebaExclusiva(){
            $resultado = $this->modelo->getPruebaExclusiva();
            return $resultado;
        }
        // Función para obtener las pruebas 4x100 existentes en la base de datos para ser mostradas en la vista
        public function getPrueba4x100(){
            $resultado = $this->modelo->getPrueba4x100();
            return $resultado;
        }
        // Función para exportar los datos de las inscripciones a pruebas 
        public function exportarInscripciones($pruebas){
            $resultado = $this->modelo->exportarInscripciones($pruebas);
            return $resultado;
        }
    }