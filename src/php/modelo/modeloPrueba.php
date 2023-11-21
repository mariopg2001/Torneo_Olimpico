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
            //funciones de formularioInscripcion tambien utiliza la funcion de pruebasyfilas
            public function alumno($idtutor,$sexo){
                $sql = 'SELECT idSeccion from Secciones where idTutor='.$idtutor;
                $result = $this->conexion->query($sql);
                $datos = $result->fetch_assoc();
                // echo $datos['idSeccion'];
                $sql2 = 'SELECT idAlumno, nombre from Alumnos WHERE idSeccion='.$datos['idSeccion']. ' AND sexo="'.$sexo.'"';
                $result2 = $this->conexion->query($sql2);
                // $datos = array($result2);
    
                return $result2;
            } 
            public function clase($idtutor){
                $sql = 'SELECT idSeccion from Secciones where idTutor='.$idtutor;
                $result = $this->conexion->query($sql);
                $datos = $result->fetch_assoc();
                return $datos['idSeccion'];
            }  
            public function altainscripcion($nombrepruebaparticipantes) {
                // var_dump($nombrepruebaparticipantes);
                $i=0;
                $j=0;
                $alumnomasc=array();
                $alumnofem=array();
                foreach ($nombrepruebaparticipantes as $prueba => $participantes) {
                    foreach ($participantes as $participante) {
                        $nombrePrueba = $participante['nombrePrueba'];
                        // Consulta SQL para insertar un participante
                        
                        if($nombrePrueba=='4x100'){
                            // echo intval($participante['idAlumno']);
                            $Prueba4x100=$nombrePrueba;

                            $clase=$participante['clase'];
                
                        if($participante['letra']=='m'){
                            $alumnomasc[$i]=intval($participante['idAlumno']);
                            $i++;
                        }else{
                            $alumnofem[$j]=intval($participante['idAlumno']);
                            $j++;
                        }

                        }else{
                            $nombrePruebaBien = str_replace("_", " ", $nombrePrueba);
                            $sql1= 'SELECT idPrueba from TO_Pruebas where nombre="'.$nombrePruebaBien.'"';
                            $result2 = $this->conexion->query($sql1);
                            $datos = $result2->fetch_assoc();
                            $sqlexclusiva= 'INSERT INTO TO_Inscripciones_Exclusivas(idAlumno,idPruebaExclusiva,sexo) values('.$participante['idAlumno'].','.$datos['idPrueba'].',"'.$participante['letra'].'")';
                            $this->conexion->query($sqlexclusiva);
                        }        
                    }     
                }
                
                if(count($alumnofem)==0){
                    unset($alumnofem);                
                }else{
                    if($Prueba4x100=="4x100"){

                        $sexo="f";
                        $sql='INSERT INTO TO_Inscripciones4x100 (idClase, participante1, participante2, participante3, participante4,sexo) values(?,?,?,?,?,?)';
                        $consulta=$this->conexion->prepare($sql);
                        $consulta->bind_param("iiiiis",$clase,$alumnofem[0],$alumnofem[1],$alumnofem[2],$alumnofem[3],$sexo);
                        $consulta->execute();
                    }
                    
                }
                if(count($alumnomasc)==0){
                    unset($alumnomasc);                
                }else{
                if($Prueba4x100=="4x100"){
                    $sexo="m";
                        $sql2='INSERT INTO TO_Inscripciones4x100 (idClase, participante1, participante2, participante3, participante4,sexo) values(?,?,?,?,?,?)';
                        $consulta2=$this->conexion->prepare($sql2);
                        $consulta2->bind_param("iiiiis",$clase,$alumnomasc[0],$alumnomasc[1],$alumnomasc[2],$alumnomasc[3],$sexo);
                        $consulta2->execute();
                    }
                }
            
                
            }
    }