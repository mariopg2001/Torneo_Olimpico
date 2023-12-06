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
        public function iniciarSesion($correo){
            try{
                $this->conectar();
                $consulta= 'SELECT * FROM Usuarios WHERE correo="'.$correo.'";';
                $result= $this->conexion->query($consulta);
                $fila_afectadas= $this->conexion->affected_rows;
                
                    while($fila = $result->fetch_assoc()){
                        // $usuarioBD=$fila['nombre'];
                        $correoBD=$fila['correo'];
                        $idusuario=$fila['idUsuario'];
                    }
                    if( $correo==$correoBD){
                        $consulta2= 'SELECT idPerfil from Perfiles_Usuarios where idUsuario='.$idusuario;
                        $result2= $this->conexion->query($consulta2);
                        $datos= $result2->fetch_assoc();

                        $consulta3 = 'SELECT nombre from Perfiles WHERE idPerfil='.$datos['idPerfil'];
                        $result3 = $this->conexion->query($consulta3);
                        $datos2= $result3->fetch_assoc();
                        $datoUsuario=array( $idusuario, $datos2['nombre']);
                        return $datoUsuario;
                    }else{
                        $mensaje ='El usuario o el correo son incorrectos';
                        echo '<script>window.location.href = "../index.php?mensaje='.$mensaje.'";</script>';
                        // header('Location: ../index.php?mensaje='.$mensaje);
                    }
                    $this->conexion->close();
                
            }catch(Exception $e){
                echo $e->getCode();
                echo $e->getMessage();
            }
        }
    }