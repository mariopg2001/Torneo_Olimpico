<?php
    require_once "../config/config.php";
    require_once('../fpdf/fpdf.php');
    class ModeloPrueba{
        
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

        //funciones de IndexPrueba

        public function pruebasyfilas(){
            $sql= 'SELECT * from TO_Pruebas';
            $result= $this->conexion->query($sql);
            $filas=$result->num_rows;

            $pruebas=array($result, $filas);
            return $pruebas;
        }
        public function responsablePrueba($id){
            $sql= 'SELECT nombre from Usuarios WHERE idUsuario='.$id;
            $result= $this->conexion->query($sql);
            $datos= $result->fetch_assoc();

            return $datos['nombre'];
        } 

        //Funciones de FormularioPrueba

        public function responsable(){
            $sql= 'SELECT * from Usuarios';
            $result= $this->conexion->query($sql);

            return $result;
            
        } 
        public function InsertarPrueba($participantes,$responsable,$nombrePrueba){
            
                $insertar='INSERT INTO TO_Pruebas(idResponsable,nombre,Max_Participantes,tipo) values ('.$responsable.',"'.$nombrePrueba.'",'.$participantes.',"E");';
               $result= $this->conexion->query($insertar);
                $idPrueba=$this->conexion->insert_id;

                $insertarExclusiva='INSERT INTO TO_Exclusivas(idPruebaExclusiva) values('.$idPrueba.');';
                $this->conexion->query($insertarExclusiva);
                if (!$result) {
                    $error=$this->conexion->errno;
                    var_dump($error);
                    if($error==1452){
                      
                        $mensaje= 'Ya hay una prueba con ese nombre';
                        echo '<script>window.location.href = "./indexPrueba.php?mensaje='.$mensaje.'";</script>';

                    }
                }else{
                    $mensaje= 'La prueba se ha guardado correctamente';
                    echo '<script>window.location.href = "./indexPrueba.php?mensaje2='.$mensaje.'";</script>';
                }
            
        }

        //Funciones form_update

        public function pruebaDatos(){
            $sql= 'SELECT * from TO_Pruebas';
            $result= $this->conexion->query($sql);
            
            return $result;
        }
        public function modificar($prueba){
            
                $sql = 'UPDATE TO_Pruebas 
                SET nombre= "'.$prueba['nombre'].'",idResponsable='.$prueba['responsable'].',Max_Participantes='.$prueba['participantes'].'
                WHERE idPrueba = '.$prueba['id'].';';
                $result = $this->conexion->query($sql);
              
                if (!$result) {
                    $error=$this->conexion->errno;
                    var_dump($error);
                    if($error==1062){
                      
                        $mensaje= 'Error al modificar, ya existe una prueba con ese nombre';
                        echo '<script>window.location.href = "./indexPrueba.php?mensaje='.$mensaje.'";</script>';

                    }
                }else{
                    $mensaje= 'La prueba se ha modificado correctamente';
                    echo '<script>window.location.href = "./indexPrueba.php?mensaje2='.$mensaje.'";</script>';
                }
              
            
        }

        //funciones de borrar

        public function borrar($id){
            try{
                $sql = " DELETE FROM TO_Exclusivas WHERE idPruebaExclusiva =".$id;
                $result = $this->conexion->query($sql);
                $sql2 = " DELETE FROM TO_Pruebas WHERE idPrueba =".$id;
                $result2 = $this->conexion->query($sql2);

                return $result2;
            }catch(Exception $e){
               echo $e->getCode();
               echo $e->getMessage();
            }
            die();
        }
       
    }