<?php
require_once "./config/config.php";
    class ModeloIndex{
        public $fila_afectadas;
        public $filas;
        private $conexion;
        public function __construct(){
            $this->conexion=$this->conectar();     
        }
        public function conectar(){
            $conexion= new mysqli(SERVER,USU,CONTRA,BBDD);
            $conexion->set_charset('utf8');
            if($conexion->connect_errno){
                echo 'la conexion falló'. $conexion->connect_error;
                die();
            }else{
                return $conexion;
            }
        }
        public function responsable($id){
            $sql= 'SELECT nombre from usuarios WHERE idUsuario='.$id;
            $result= $this->conexion->query($sql);
            $datos= $result->fetch_assoc();
            return $datos['nombre'];
            
        } 
        public function pruebas(){
            $sql= 'SELECT * from to_pruebas';
            $result= $this->conexion->query($sql);
            
            return $result;
        }
       
    }