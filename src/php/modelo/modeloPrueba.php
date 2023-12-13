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

            //funciones pdf
            public function generarPDF($pruebas) {
                foreach($pruebas as $prueba){
                    $sql = "SELECT * FROM TO_Pruebas WHERE idPrueba = ".$prueba."";
                    $resultado = $this->conexion->query($sql);
                    if($resultado){
                        $fila = $resultado->fetch_assoc();
                        if($fila['tipo'] == '4'){
                            $array4x100 = $fila['idPrueba'];
                        }else{
                            $arrayExclusiva[] = $fila['idPrueba'];
                        }
                    }
                }
                if(isset($array4x100)){
                    // echo "4x100";
                    $nombrePrueba = '4x100';
                    // $idPrueba = intval($datosPruebas['idPrueba']);
                    $sql = "SELECT a.nombre as 'nombreAlumno', s.nombre as 'nombreSeccion', a.sexo, ca.nombre as 'nombreCategoria' FROM Alumnos a 
                                INNER JOIN Secciones s ON a.idSeccion = s.idSeccion 
                                INNER JOIN Cursos c ON c.idCurso = s.idCurso 
                                INNER JOIN Etapas e ON e.idEtapa = c.idEtapa 
                                INNER JOIN TO_CategoriasEtapas ce ON ce.idEtapa = e.idEtapa
                                INNER JOIN TO_Categorias ca ON ce.idCategoria = ca.idCategoria
                                INNER JOIN TO_Inscripciones4x100 ic ON ic.participante1 = a.idAlumno OR ic.participante2 = a.idAlumno OR ic.participante3 = a.idAlumno OR ic.participante4 = a.idAlumno
                                    ORDER BY ca.idCategoria, a.sexo, s.idSeccion, a.nombre";

                    $resultado = $this->conexion->query($sql);
                    
                            ob_clean();
                            $pdf = new FPDF();
                            $pdf->AddPage();
                            $pdf->SetFont('Arial','B',16);
                            $pdf->Cell(0,10,utf8_decode('Torneo Olímpico'),0,1,'C');
                            $categoriaActual = null; // Inicializar la categoría actual
                            $sexoActual = null; // Inicializar el sexo actual
                            $i=1;
                            while ($linea = $resultado->fetch_assoc()) {
                                $categoria = $linea['nombreCategoria'];
                                $sexo = $linea['sexo'];
                            
                                if ($categoria != $categoriaActual || $sexo != $sexoActual) {
                                    if ($categoriaActual !== null) {
                                        $pdf->AddPage();
                                        $i=1;
                                        // Agregar encabezado de la tabla en la nueva página
                                    }
                                    $categoriaActual = $categoria;
                                    $sexoActual = $sexo;
                                    if($sexo=='m'){
                                        $sexo2='Masculino';
                                    } else {
                                        $sexo2='Femenino';
                                    }
                                    $pdf->SetFont('Arial','B',10);
                                    $pdf->Cell(0,10,'Prueba: '.$nombrePrueba.' Cat: '. $categoria.' Sexo:'. $sexo2,0,1,'C');
                                    $pdf->Ln(20);
                                    $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                                    $pdf->Cell(20, 10, utf8_decode('Nº'), 1, 0, 'C');
                                    $pdf->Cell(80, 10, 'Nombre del Alumno', 1, 0, 'C'); // Ajustar el ancho de la celda
                                    $pdf->Cell(40, 10, 'Clase', 1, 0, 'C');
                                    $pdf->Cell(30, 10, 'Marca', 1, 1, 'C');
                                }
                                $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                            
                                $nombre = $linea['nombreAlumno'];
                                $seccion = $linea['nombreSeccion'];
                                $pdf->SetFont('Arial','',9);
                                $pdf->Cell(20,10,$i, 1, 0, 'C');
                                $pdf->Cell(80,10,utf8_decode($nombre), 1, 0, 'C');
                                $pdf->Cell(40,10,utf8_decode($seccion), 1, 0, 'C');
                                $pdf->Cell(30,10,'', 1, 1, 'C');
                                $i++;
                            
                            }
                            $zip = new ZipArchive();
                            $zipFileName = 'Inscripciones.zip';
                            if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
                                // Agregar el archivo PDF al ZIP
                                $zip->addFile('Inscripciones'.$nombrePrueba.'.pdf', 'Inscripciones'.$nombrePrueba.'.pdf');
                                $zip->close();
                                // Descargar el archivo ZIP
                                header('Content-Type: application/zip');
                                header('Content-disposition: attachment; filename='.$zipFileName);
                                header('Content-Length: ' . filesize($zipFileName));
                                readfile($zipFileName);     
                            }
                            $pdf->Output('Inscripciones'.$nombrePrueba.'.pdf', 'F'); // Guardar el archivo PDF en el servidor
                        
                    

                }
                if(isset($arrayExclusiva)){   
                    // echo "Exclusiva";
                    foreach($pruebas as $prueba){
                        $sql = "SELECT * FROM TO_Pruebas WHERE idPrueba = ".$prueba."";
                        $result = $this->conexion->query($sql);
                        ob_clean();
                        $i=1;
                        while($datosPruebas = $result->fetch_assoc()){
                            $nombrePrueba = $datosPruebas['nombre'];
                            $idPrueba = intval($datosPruebas['idPrueba']);
                            $sql = "SELECT a.nombre as 'nombreAlumno', s.nombre as 'nombreSeccion', a.sexo, ca.nombre as 'nombreCategoria' FROM Alumnos a 
                                        INNER JOIN Secciones s ON a.idSeccion = s.idSeccion 
                                        INNER JOIN Cursos c ON c.idCurso = s.idCurso 
                                        INNER JOIN Etapas e ON e.idEtapa = c.idEtapa 
                                        INNER JOIN TO_CategoriasEtapas ce ON ce.idEtapa = e.idEtapa
                                        INNER JOIN TO_Categorias ca ON ce.idCategoria = ca.idCategoria
                                        INNER JOIN TO_Inscripciones_Exclusivas ie ON ie.idAlumno = a.idAlumno
                                            WHERE ie.idPruebaExclusiva = $idPrueba
                                            ORDER BY ca.idCategoria, a.sexo, s.idSeccion, a.nombre";
                            $resultado = $this->conexion->query($sql);
                            
                            $pdf = new FPDF();
                            $pdf->AddPage();
                            $pdf->SetFont('Arial','B',16);
                            $pdf->Cell(0,10,utf8_decode('Torneo Olímpico'),0,1,'C');
                            $categoriaActual = null; // Inicializar la categoría actual
                            $sexoActual = null; // Inicializar el sexo actual
                            
                            while ($linea = $resultado->fetch_assoc()) {
                                $categoria = $linea['nombreCategoria'];
                                $sexo = $linea['sexo'];
                            
                                if ($categoria != $categoriaActual || $sexo != $sexoActual) {
                                    if ($categoriaActual !== null) {
                                        $pdf->AddPage();
                                        $i=1;
                                        // Agregar encabezado de la tabla en la nueva página
                                    }
                                    $categoriaActual = $categoria;
                                    $sexoActual = $sexo;
                                    if($sexo=='m'){
                                        $sexo2='Masculino';
                                    } else {
                                        $sexo2='Femenino';
                                    }
                                    $pdf->SetFont('Arial','B',10);
                                    $pdf->Cell(0,10,'Prueba: '.$nombrePrueba.' Cat: '. $categoria.' Sexo:'. $sexo2,0,1,'C');
                                    $pdf->Ln(20);
                                    $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                                    $pdf->Cell(20, 10, utf8_decode('Nº'), 1, 0, 'C');
                                    $pdf->Cell(80, 10, 'Nombre del Alumno', 1, 0, 'C'); // Ajustar el ancho de la celda
                                    $pdf->Cell(40, 10, 'Clase', 1, 0, 'C');
                                    $pdf->Cell(30, 10, 'Marca', 1, 1, 'C');
                                }
                                $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                            
                                $nombre = $linea['nombreAlumno'];
                                $seccion = $linea['nombreSeccion'];
                                $pdf->SetFont('Arial','',9);
                                $pdf->Cell(20,10,$i, 1, 0, 'C');
                                $pdf->Cell(80,10,utf8_decode($nombre), 1, 0, 'C');
                                $pdf->Cell(40,10,utf8_decode($seccion), 1, 0, 'C');
                                $pdf->Cell(30,10,'', 1, 1, 'C');
                                $i++;
                            
                            }
                            $zip = new ZipArchive();
                            $zipFileName = 'Inscripciones.zip';
                            if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
                                // Agregar el archivo PDF al ZIP
                                $zip->addFile('Inscripciones'.$nombrePrueba.'.pdf', 'Inscripciones'.$nombrePrueba.'.pdf');
                                $zip->close();
                                // Descargar el archivo ZIP
                                header('Content-Type: application/zip');
                                header('Content-disposition: attachment; filename='.$zipFileName);
                                header('Content-Length: ' . filesize($zipFileName));
                                readfile($zipFileName);     
                            }
                            $pdf->Output('Inscripciones'.$nombrePrueba.'.pdf', 'F'); // Guardar el archivo PDF en el servidor
                        }
                    }
                    
                    // Crear un archivo ZIP
                    
                   

                }
                ob_end_clean(); 
                    readfile($zipFileName);
    
                    // Elimina el archivo ZIP después de la descarga
                    unlink($zipFileName);
               
            }
            public function generarPDFTodas() {
                // $nombrePrueba = '4x100';
               
                $sql2 = "SELECT * FROM TO_Pruebas";
                $result = $this->conexion->query($sql2);
                while ($row = $result->fetch_assoc()) {
                    $nombrePrueba = $row['nombre']; // Obtener el nombre de la prueba
                    // echo $nombrePrueba;
                    if($nombrePrueba=='4x100'){
                        $sql = "SELECT a.nombre as 'nombreAlumno', s.nombre as 'nombreSeccion', a.sexo, ca.nombre as 'nombreCategoria' FROM Alumnos a 
                        INNER JOIN Secciones s ON a.idSeccion = s.idSeccion 
                        INNER JOIN Cursos c ON c.idCurso = s.idCurso 
                        INNER JOIN Etapas e ON e.idEtapa = c.idEtapa 
                        INNER JOIN TO_CategoriasEtapas ce ON ce.idEtapa = e.idEtapa
                        INNER JOIN TO_Categorias ca ON ce.idCategoria = ca.idCategoria
                        INNER JOIN TO_Inscripciones4x100 ic ON ic.participante1 = a.idAlumno OR ic.participante2 = a.idAlumno OR ic.participante3 = a.idAlumno OR ic.participante4 = a.idAlumno
                        ORDER BY ca.idCategoria, a.sexo, s.idSeccion, a.nombre";
                        $resultado = $this->conexion->query($sql);                 
                        ob_clean();
                        $pdf = new FPDF();
                        $pdf->AddPage();
                        $pdf->SetFont('Arial','B',16);
                        $pdf->Cell(0,10,utf8_decode('Torneo Olímpico'),0,1,'C');
                        $categoriaActual = null; // Inicializar la categoría actual
                        $sexoActual = null; // Inicializar el sexo actual
                        $i=1;
                        while ($linea = $resultado->fetch_assoc()) {
                            $categoria = $linea['nombreCategoria'];
                            $sexo = $linea['sexo'];
                            if ($categoria != $categoriaActual || $sexo != $sexoActual) {
                                if ($categoriaActual !== null) {
                                    $pdf->AddPage();
                                    $i=1;
                                    // Agregar encabezado de la tabla en la nueva página
                                }
                                $categoriaActual = $categoria;
                                $sexoActual = $sexo;
                                if($sexo=='m'){
                                    $sexo2='Masculino';
                                } else {
                                    $sexo2='Femenino';
                                }
                                $pdf->SetFont('Arial','B',10);
                                $pdf->Cell(0,10,'Prueba: '.$nombrePrueba.' Cat: '. $categoria.' Sexo:'. $sexo2,0,1,'C');
                                $pdf->Ln(20);
                                $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                                $pdf->Cell(20, 10, utf8_decode('Nº'), 1, 0, 'C');
                                $pdf->Cell(80, 10, 'Nombre del Alumno', 1, 0, 'C'); // Ajustar el ancho de la celda
                                $pdf->Cell(40, 10, 'Clase', 1, 0, 'C');
                                $pdf->Cell(30, 10, 'Marca', 1, 1, 'C');
                            }
                            $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                            $nombre = $linea['nombreAlumno'];
                            $seccion = $linea['nombreSeccion'];
                            $pdf->SetFont('Arial','',9);
                            $pdf->Cell(20,10,$i, 1, 0, 'C');
                            $pdf->Cell(80,10,utf8_decode($nombre), 1, 0, 'C');
                            $pdf->Cell(40,10,utf8_decode($seccion), 1, 0, 'C');
                            $pdf->Cell(30,10,'', 1, 1, 'C');
                            $i++;

                        }

                    }else{
        
                        $idsPruebas = array(); // Crear un array para almacenar los IDs de las pruebas
                
                        foreach($result as $datosPruebas) {
                            $idsPruebas[] = intval($datosPruebas['idPrueba']); // Almacenar el ID de la prueba en el array
                        }
                        array_shift($idsPruebas);
                      
                        foreach($idsPruebas as $prueba){
                            // echo $prueba.' ';
                            $sql = "SELECT * FROM TO_Pruebas WHERE idPrueba = ".$prueba."";
                                $resultado = $this->conexion->query($sql);
                            // echo $sql.'<br/>';
                            $i=1;
                            while($datosPruebas = $resultado->fetch_assoc()){
                                $nombrePrueba = $datosPruebas['nombre'];
                                $idPrueba = intval($datosPruebas['idPrueba']);
                                $sql3 = "SELECT a.nombre as 'nombreAlumno', s.nombre as 'nombreSeccion', a.sexo, ca.nombre as 'nombreCategoria' FROM Alumnos a 
                                            INNER JOIN Secciones s ON a.idSeccion = s.idSeccion 
                                            INNER JOIN Cursos c ON c.idCurso = s.idCurso 
                                            INNER JOIN Etapas e ON e.idEtapa = c.idEtapa 
                                            INNER JOIN TO_CategoriasEtapas ce ON ce.idEtapa = e.idEtapa
                                            INNER JOIN TO_Categorias ca ON ce.idCategoria = ca.idCategoria
                                            INNER JOIN TO_Inscripciones_Exclusivas ie ON ie.idAlumno = a.idAlumno
                                                WHERE ie.idPruebaExclusiva = $idPrueba
                                                ORDER BY ca.idCategoria, a.sexo, s.idSeccion, a.nombre";
                                $resultado2 = $this->conexion->query($sql3);
                                
                                if ($resultado2->num_rows > 0) {
                                    $pdf->AddPage();
                                    $pdf->SetFont('Arial','B',16);
                                    $pdf->Cell(0,10,utf8_decode('Torneo Olímpico'),0,1,'C');
                                    $categoriaActual = null; // Inicializar la categoría actual
                                    $sexoActual = null; // Inicializar el sexo actual
                                    while ($linea = $resultado2->fetch_assoc()) {
                                        $categoria = $linea['nombreCategoria'];
                                        $sexo = $linea['sexo'];
                                    
                                        if ($categoria != $categoriaActual || $sexo != $sexoActual) {
                                            if ($categoriaActual !== null) {
                                                $pdf->AddPage();
                                                $i=1;
                                                // Agregar encabezado de la tabla en la nueva página
                                            }
                                            $categoriaActual = $categoria;
                                            $sexoActual = $sexo;
                                            if($sexo=='m'){
                                                $sexo2='Masculino';
                                            } else {
                                                $sexo2='Femenino';
                                            }
                                            $pdf->SetFont('Arial','B',10);
                                            $pdf->Cell(0,10,'Prueba: '.$nombrePrueba.' Cat: '. $categoria.' Sexo:'. $sexo2,0,1,'C');
                                            $pdf->Ln(20);
                                            $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                                            $pdf->Cell(20, 10, utf8_decode('Nº'), 1, 0, 'C');
                                            $pdf->Cell(80, 10, 'Nombre del Alumno', 1, 0, 'C'); // Ajustar el ancho de la celda
                                            $pdf->Cell(40, 10, 'Clase', 1, 0, 'C');
                                            $pdf->Cell(30, 10, 'Marca', 1, 1, 'C');
                                        }
                                        $pdf->SetX(25); // Ajustar la posición X para el contenido de la tabla
                                    
                                        $nombre = $linea['nombreAlumno'];
                                        $seccion = $linea['nombreSeccion'];
                                        $pdf->SetFont('Arial','',9);
                                        $pdf->Cell(20,10,$i, 1, 0, 'C');
                                        $pdf->Cell(80,10,utf8_decode($nombre), 1, 0, 'C');
                                        $pdf->Cell(40,10,utf8_decode($seccion), 1, 0, 'C');
                                        $pdf->Cell(30,10,'', 1, 1, 'C');
                                        $i++;
                                
                                    }
                                }
                            }
                        }  
                        $pdf->Output('InscripcionesCompletas.pdf', 'D'); // Guardar el archivo PDF en el servidor

                    }
                }
            }
    }

        
    // 
