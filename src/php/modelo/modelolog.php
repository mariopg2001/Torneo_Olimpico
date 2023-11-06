<?php
    require_once '../config/config.php';
    class Modelolog{
       private $conexion;
    //    public $fila_afectadas;
        public function __construct(){
            $this->conexion=$this->conectar();
        }
        public function conectar(){
            $conexion= new mysqli(SERVER,USU,CONTRA,BBDD) or die ('No se puede conectar');
            $conexion->set_charset('utf8');

            return $conexion;
        }
        public function iniciarSesion($correo,$usuario){
            try{
                $this->conectar();
                $consulta= 'SELECT * FROM Usuarios WHERE correo="'.$correo.'";';
                $result= $this->conexion->query($consulta);
                $fila_afectadas= $this->conexion->affected_rows;
                echo ''.$fila_afectadas.'';
                if( $fila_afectadas<= 0){
                    $mensaje ='El usuario o el correo son incorrectos';
                    // echo '<script>window.location.href = "../index.php?mensaje='.$mensaje.'";</script>';
                    header('Location: ../index.php?mensaje='.$mensaje);

                }
                else{
                   
                    while($fila = $result->fetch_assoc()){
                        $usuarioBD=$fila['nombre'];
                        $correoBD=$fila['correo'];
                        $idusuario=$fila['idUsuario'];
                    }
                    if($usuarioBD==$usuario && $correo==$correoBD){
                        echo $idusuario;
                        return $idusuario;
                    }
                    $this->conexion->close();
                }
            }catch(Exception $e){
                echo $e->getCode();
                echo $e->getMessage();
            }
        }
    }