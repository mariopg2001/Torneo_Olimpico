<?php
require_once "../config/config.php";
    class ModeloPrueba{
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
        public function responsable(){
            $sql= 'SELECT * from usuarios';
            $result= $this->conexion->query($sql);
            return $result;
            
        } 
      
        public function InsertarPrueba( $participantes,$finicio,$Ffin,$responsable,$nombrePrueba){
          
            try{
                $insertar='INSERT INTO to_pruebas(idResponsable,nombre,fInicioInscripcion,fFinInscripcion,Max_Participantes,tipo) values ('.$responsable.',"'.$nombrePrueba.'","'.$finicio.'","'.$Ffin.'",'.$participantes.',"E");';
                $this->conexion->query($insertar);
                $idPrueba=$this->conexion->insert_id;
                $insertarExclusiva='INSERT INTO to_exclusivas(idPruebaExclusiva) values('.$idPrueba.');';
                $this->conexion->query($insertarExclusiva);
            }catch(Exception $e){
                // echo $e->getCode();
                // echo $e->getMessage();
                if($e->getCode()==1146){
                    echo 'Ya existe una Prueba con ese nombre';
                } 
            }
        }
        public function Prueba(){
            $sql= 'SELECT * from to_pruebas';
            $result= $this->conexion->query($sql);
            
            return $result;
        }
        public function modificar($prueba){
            var_dump($prueba);
            try{
                $sql = 'UPDATE to_pruebas 
                SET nombre= "'.$prueba['nombre'].'",idResponsable='.$prueba['responsable'].',Max_Participantes='.$prueba['participantes'].'
                WHERE idPrueba = '.$prueba['id'].';';
                $result = $this->conexion->query($sql);
                return $result;
            }catch(Exception $e){
                // echo $e->getCode();
                // echo $e->getMessage();
                if($e->getCode()==1146){
                    echo 'Ya existe una Prueba con ese nombre';
                } 
            }
        }
        public function borrar($id){
            try{
                
                $sql = " DELETE FROM to_exclusivas WHERE idPruebaExclusiva =".$id;
                $result = $this->conexion->query($sql);
                $sql2 = " DELETE FROM to_pruebas WHERE idPrueba =".$id;
                $result2 = $this->conexion->query($sql2);

                return $result2;
            }catch(Exception $e){
            //    echo $e->getCode();
            //    echo $e->getMessage();
               if( $e->getCode()== '1451'){
                    echo 'No se puede borrar debido a que el campo está asociado como clave ajena a otro campo ';
                }
            }
            die();
        }
    }