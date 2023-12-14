
<?php
require_once '../modelo/modeloInscripciones.php';
    
    class ControladorInscripcion{
        private $modelo;   
        public function __construct()
        {
            $this->modelo = new ModeloInscripcion();
        }

        //funciones formularioInscripcion
        public function pruebasyfilas(){
            $resultado=$this->modelo->pruebasyfilas();
            return $resultado;
        }
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
    }
?>