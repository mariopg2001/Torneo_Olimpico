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
                    echo '<script>window.location.href = "./indexPrueba.php?mensaje='.$mensaje.'";</script>';
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
                    echo '<script>window.location.href = "./indexPrueba.php?mensaje='.$mensaje.'";</script>';
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
            public function participantes4x100($idtutor){
                $sql = 'SELECT idSeccion from Secciones where idTutor='.$idtutor;
                $result = $this->conexion->query($sql);
                $datos = $result->fetch_assoc();
                
                $sql2 = 'SELECT participante1,participante2,participante3,participante4,sexo from TO_Inscripciones4x100 where idClase='.$datos['idSeccion'];;
                $result2 = $this->conexion->query($sql2);
                $filas=$result2->num_rows;
                $pruebas=array($result2, $filas);
                return $pruebas;
                
            }
            public function participantesExclusiva($prueba, $idtutor){
                $sql = 'SELECT idSeccion from Secciones where idTutor='.$idtutor;
                $result = $this->conexion->query($sql);
                $datos = $result->fetch_assoc();

                $sql1= 'SELECT idPrueba from TO_Pruebas where nombre="'.$prueba.'"';
                $result1 = $this->conexion->query($sql1);
                $datos2 = $result1->fetch_assoc();
                    
                $sql2 = 'SELECT TO_ins.idAlumno,TO_ins.sexo from TO_Inscripciones_Exclusivas AS TO_ins INNER JOIN Alumnos AS al on TO_ins.idAlumno= al.idAlumno where idPruebaExclusiva='.$datos2['idPrueba'].' AND al.idSeccion='.$datos['idSeccion'];
                $result2 = $this->conexion->query($sql2);
                 $filas2=$result2->num_rows;
                
                $pruebas=array($result2, $filas2);
                return $pruebas;
            } 
            public function consultaInscripciones($idclase){
                
                $sql1= 'SELECT TO_ins.idAlumno from TO_Inscripciones_Exclusivas AS TO_ins INNER JOIN Alumnos AS al on TO_ins.idAlumno= al.idAlumno WHERE idSeccion='.$idclase;
                $result1 = $this->conexion->query($sql1);
                $filas1=$result1->num_rows;

                $sql2 = 'SELECT idClase from TO_Inscripciones4x100 where idclase='.$idclase;
                $result2 = $this->conexion->query($sql2);
                $filas2=$result2->num_rows;
                $filas3=$filas1+$filas2;
                return $filas3;
            } 
            public function BorrarInscripcionesdeClase($idclase){
                $sql = "DELETE FROM TO_Inscripciones_Exclusivas
                WHERE idAlumno IN (SELECT idAlumno FROM Alumnos WHERE idSeccion=".$idclase. ")";
                $this->conexion->query($sql);
                $sql2="DELETE FROM TO_Inscripciones4x100 WHERE idClase=".$idclase;
                $this->conexion->query($sql2);

            }  
            public function fechasInscripcion(){
                $consulta='SELECT * FROM TO_FechaInscripcion';
                $result=$this->conexion->query($consulta);
                $datos = $result->fetch_assoc();
                $fechas=array($datos['fechaInicio'],$datos['fechaFin']);
                return $fechas;
            }  
    }