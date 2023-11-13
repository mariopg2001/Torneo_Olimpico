<?php
    require_once "../config/config.php";

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
        public function tipoUsuario($idusuario){
            $sql= 'SELECT idPerfil from Perfiles_Usuarios where idUsuario='.$idusuario;
            $result= $this->conexion->query($sql);
            $datos= $result->fetch_assoc();

            $sql2 = 'SELECT nombre from Perfiles WHERE idPerfil='.$datos['idPerfil'];
            $result2 = $this->conexion->query($sql2);
            $datos2= $result2->fetch_assoc();

            return $datos2['nombre'];
        }
        public function nombreUsuario($idusuario){
            $sql= 'SELECT nombre from Usuarios where idUsuario='.$idusuario;
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
            try{
                
                $insertar='INSERT INTO TO_Pruebas(idResponsable,nombre,Max_Participantes,tipo) values ('.$responsable.',"'.$nombrePrueba.'",'.$participantes.',"E");';
                $this->conexion->query($insertar);
                $idPrueba=$this->conexion->insert_id;

                $insertarExclusiva='INSERT INTO TO_Exclusivas(idPruebaExclusiva) values('.$idPrueba.');';
                $this->conexion->query($insertarExclusiva);

            }catch(Exception $e){
                if($e->getCode()==1146){
                    echo 'Ya existe una Prueba con ese nombre';
                } 
            }
        }

        //Funciones form_update

        public function pruebaDatos(){
            $sql= 'SELECT * from TO_Pruebas';
            $result= $this->conexion->query($sql);
            
            return $result;
        }
        public function modificar($prueba){
            try{
                $sql = 'UPDATE TO_Pruebas 
                SET nombre= "'.$prueba['nombre'].'",idResponsable='.$prueba['responsable'].',Max_Participantes='.$prueba['participantes'].'
                WHERE idPrueba = '.$prueba['id'].';';
                $result = $this->conexion->query($sql);
                return $result;
            }catch(Exception $e){
                if($e->getCode()==1146){
                    echo 'Ya existe una Prueba con ese nombre';
                } 
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