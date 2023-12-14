<?php
    require_once '../modelo/modeloFechas.php';
    
    class ControladorFechas{
    
        private $modelo;   
        public function __construct(){
            $this->modelo = new ModeloFechas();
        }
        // Función para obtener la fecha inicio y la fecha de fin del periodo de inscripciones
        public function getFechas(){
            $resultado = $this->modelo->getFechas();
            return $resultado;
        }
        // Función para modificar la fecha de inscripción en la base de datos
        public function modificarFechas($fechaInicio, $fechaFin){
            $resultado = $this->modelo->modificarFechas($fechaInicio,$fechaFin);
            return $resultado;
        }
    }