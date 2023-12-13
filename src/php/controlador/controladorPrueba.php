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
          //funciones formularioInscripcion
          public function alumno($idtutor,$sexo){
            $resultado=$this->modelo->alumno($idtutor,$sexo);
            return $resultado;
        }
        public function clase($idtutor){
            $resultado=$this->modelo->clase($idtutor);
            return $resultado;
        }
         public function altainscripcion($nombrepruebaparticipantes){
            $resultado=$this->modelo->altainscripcion($nombrepruebaparticipantes);
            return $resultado;
        }

        public function participantes4x100($idtutor){
            $resultado=$this->modelo->participantes4x100($idtutor);
            return $resultado;
        }
        public function participantesExclusiva($prueba,$idtutor){
            $resultado=$this->modelo->participantesExclusiva($prueba,$idtutor);
            return $resultado;
        }
        public function consultaInscripciones($idclase){
            $resultado=$this->modelo->consultaInscripciones($idclase);
            return $resultado;
        }
        public function BorrarInscripcionesdeClase($idclase){
            $resultado=$this->modelo->BorrarInscripcionesdeClase($idclase);
            return $resultado;
        }
        public function fechasInscripcion(){
            $resultado=$this->modelo->fechasInscripcion();
            return $resultado;
        }

        //funciones pdf
        public function generarPDF($pruebas){
            $resultado=$this->modelo->generarPDF($pruebas);
            return $resultado;
        }
        public function generarPDFTodas(){
            $resultado=$this->modelo->generarPDFTodas();
            return $resultado;
        }
    
    }