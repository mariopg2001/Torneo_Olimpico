<?php
    require '../../../vendor/autoload.php';
    require_once "../config/config.php";
    require_once('../fpdf/fpdf.php');
    //use PhpOffice\PhpSpreadsheet\{Spreadsheetl,IOFactory};
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    
    class ModeloExcel{
        public $filas;
        private $conexion;
        public function __construct(){
            $this->conexion=$this->conectar();
        }
        public function conectar(){
            $conexion = new mysqli(SERVER,USU,CONTRA,BBDD);
            $conexion->set_charset('utf8');
            if($conexion->connect_errno){
                echo 'la conexion falló'. $conexion->connect_error;
                die();
            }else{
                return $conexion;
            }
        }
        public function getPruebaExclusiva(){
            $sql = 'SELECT idPrueba, nombre, COUNT(idPruebaExclusiva) AS num_inscripciones, tipo FROM TO_Pruebas 
                        LEFT JOIN TO_Inscripciones_Exclusivas ON idPrueba = idPruebaExclusiva
                        WHERE tipo = "E"
                        GROUP BY idPrueba';
            $result = $this->conexion->query($sql);

            $sql1 = 'SELECT * from TO_Pruebas';
            $result1 = $this->conexion->query($sql1);

            return $result;
        }
        public function getPrueba4x100(){
            $sql = 'SELECT idPrueba, nombre, tipo, (SELECT COUNT(*) FROM TO_Inscripciones4x100) AS num_inscripciones
                        FROM TO_Pruebas
                        WHERE tipo = "4";';
            $result = $this->conexion->query($sql);

            return $result;
        }
        public function exportarInscripciones($pruebas){
            try{
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
                
                // var_dump($pruebas);
                $nombreZip = 'Inscripciones.zip';   // Nombre del archivo ZIP
                $zip = new ZipArchive();

                // Intenta abrir el archivo ZIP para escritura
                if ($zip->open($nombreZip, ZipArchive::CREATE) !== true) {
                    throw new Exception('No se pudo crear el archivo ZIP');
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
                    //echo "Consulta SQL: ".$sql;
                    
                    




                    // Creo un nuevo objeto Spreadsheet
                    $excel = new Spreadsheet();
                    $excel->removeSheetByIndex(0);  // Elimina la hoja 'Worksheet'

                    // Organizo los datos en hojas de cálculo separadas por categoría y sexo
                    while($fila = $resultado->fetch_assoc()){
                        $categoria = $fila['nombreCategoria'];
                        if($fila['sexo'] == 'f'){
                            $sexo = 'fem.';
                        }elseif($fila['sexo'] == 'm'){
                            $sexo = 'masc.';
                        }
                        // Verifico si la hoja de cálculo ya existe
                        $hoja = $excel->getSheetByName("ctg ".$categoria."-".$sexo);
                        if(!$hoja){
                            // Si no existe, creo una nueva hoja
                            $hoja = $excel->createSheet();
                            $hoja->setTitle("ctg $categoria-$sexo");
                            $i = 1;
                        }

                        // Añado datos a la hoja de cálculo
                        $hoja->setCellValue('B2', 'INSCRIPCIONES TORNEO OLIMPICO');
                        $hoja->getStyle('B2')->getFont()->setBold(true);
                        $hoja->getColumnDimension('B')->setAutoSize(true);
                        $hoja->setCellValue('B4', 'Prueba: '.$nombrePrueba);
                        $hoja->setCellValue('B7', 'Apellidos, Nombre');
                        $hoja->setCellValue('C7', 'Clase');
                        $hoja->getColumnDimension('C')->setAutoSize(true);
                        $hoja->getStyle('B2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('90CFE7');
                        $hoja->getStyle('B4:D5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('90CFE7');
                        $hoja->getStyle('A7:D7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('90CFE7');

                        // Agrego datos específicos de la fila
                        $row = $hoja->getHighestRow() + 1;
                        $hoja->setCellValue("A$row", $i);
                        $hoja->setCellValue("B$row", $fila['nombreAlumno']);
                        $hoja->setCellValue("C$row", $fila['nombreSeccion']);
                        $i++;
                        $hoja->getStyle("A7:D$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                    }
                    // Agregar el archivo Excel al archivo ZIP
                    $nombreExcel = $nombrePrueba.'.xlsx';
                    $carpetaDescarga = sys_get_temp_dir();
                    $directorioExcel = $carpetaDescarga.'/'.$nombreExcel;
                    $excel->setActiveSheetIndex(0);
                    $writer = IOFactory::createWriter($excel, 'Xlsx');
                    $writer->save($directorioExcel);
                    $zip->addFile($directorioExcel, $nombreExcel);
                    // Eliminar el primer elemento del array
                    array_shift($pruebas);
                }
                if(isset($arrayExclusiva)){   
                    // echo "Exclusiva";
                    foreach($pruebas as $prueba){
                        $sql = "SELECT * FROM TO_Pruebas WHERE idPrueba = ".$prueba."";
                        $result = $this->conexion->query($sql);
                        
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
                            //echo "Consulta SQL: ".$sql;
                            
                            // Creo un nuevo objeto Spreadsheet
                            $excel = new Spreadsheet();
                            $excel->removeSheetByIndex(0);  // Elimina la hoja 'Worksheet'
    
                            // Organizo los datos en hojas de cálculo separadas por categoría y sexo
                            while($fila = $resultado->fetch_assoc()){
                                $categoria = $fila['nombreCategoria'];
                                if($fila['sexo'] == 'f'){
                                    $sexo = 'fem.';
                                }elseif($fila['sexo'] == 'm'){
                                    $sexo = 'masc.';
                                }
                                // Verifico si la hoja de cálculo ya existe
                                $hoja = $excel->getSheetByName("ctg ".$categoria."-".$sexo);
                                if(!$hoja){
                                    // Si no existe, creo una nueva hoja
                                    $hoja = $excel->createSheet();
                                    $hoja->setTitle("ctg $categoria-$sexo");
                                    $i = 1;
                                }
    
                                // Añado datos a la hoja de cálculo
                                $hoja->setCellValue('B2', 'INSCRIPCIONES TORNEO OLIMPICO');
                                $hoja->getStyle('B2')->getFont()->setBold(true);
                                $hoja->getColumnDimension('B')->setAutoSize(true);
                                $hoja->setCellValue('B4', 'Prueba: '.$nombrePrueba);
                                $hoja->setCellValue('B7', 'Apellidos, Nombre');
                                $hoja->setCellValue('C7', 'Clase');
                                $hoja->getColumnDimension('C')->setAutoSize(true);
                                $hoja->getStyle('B2:D2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('90CFE7');
                                $hoja->getStyle('B4:D5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('90CFE7');
                                $hoja->getStyle('A7:D7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('90CFE7');
    
                                // Agrego datos específicos de la fila
                                $row = $hoja->getHighestRow() + 1;
                                $hoja->setCellValue("A$row", $i);
                                $hoja->setCellValue("B$row", $fila['nombreAlumno']);
                                $hoja->setCellValue("C$row", $fila['nombreSeccion']);
                                $i++;
                                $hoja->getStyle("A7:D$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                            }
                        }
                        // Agregar el archivo Excel al archivo ZIP
                        $nombreExcel = $nombrePrueba.'.xlsx';
                        $carpetaDescarga = sys_get_temp_dir();
                        $directorioExcel = $carpetaDescarga.'/'.$nombreExcel;
                        $excel->setActiveSheetIndex(0);
                        $writer = IOFactory::createWriter($excel, 'Xlsx');
                        $writer->save($directorioExcel);
                        $zip->addFile($directorioExcel, $nombreExcel);
                    }
                }
                // Cierra el archivo ZIP
                $zip->close();

                // Configura los encabezados para la descarga del archivo ZIP
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="'.$nombreZip.'"');
                header('Content-Length: '.filesize($nombreZip));

                ob_end_clean();     // Limpia el búffer de salida y su contenido sin enviar nada al navegador
// Lee el archivo ZIP y envía el contenido al navegador
readfile($nombreZip);

// Elimina el archivo ZIP después de la descarga
unlink($nombreZip);

// Agrega un script JavaScript para redirigir al usuario después de la descarga
echo '<script>window.location = "./indexPrueba.php";</script>';
return 0;
            }catch(Exception $e){
                echo 'Error: ',  $e->getMessage(), "\n";
            }
        }
    }