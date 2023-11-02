<?php
    require_once '../config/config.php';
    class Modelolog{
       private $conexion;

        public function __construct(){
            $this->conexion=$this->conectar();
        }
        public function conectar(){
            $conexion= new mysqli(SERVER,USU,CONTRA,BBDD) or die ('No se puede conectar');
            $conexion->set_charset('utf8');

            return $conexion;
        }
        public function iniciarSesion($correo,$usuario){
            $this->conectar();
            $consulta= 'SELECT * FROM usuarios WHERE correo="'.$correo.'"    ;';
            $result= $this->conexion->query($consulta);

            while($fila = $result->fetch_assoc()){
                $usuarioBD=$fila['nombre'];
                $correoBD=$fila['correo'];
            }
            if($usuarioBD==$usuario && $correo==$correoBD){
                
                return $usuario;
                
            }
            $this->conexion->close();
        }
    }