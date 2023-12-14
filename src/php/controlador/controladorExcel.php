<?php
    require_once '../modelo/modeloExcel.php';
    require '../../../vendor/autoload.php';
    require_once('../fpdf/fpdf.php');
    
    //use PhpOffice\PhpSpreadsheet\{Spreadsheetl,IOFactory};
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\IOFactory;

    class ControladorExcel{
    
        private $modelo;   
        public function __construct(){
            $this->modelo = new ModeloExcel();
        }
        // Función para obtener las pruebas exclusivas existentes en la base de datos para ser mostradas en la vista
        public function getPruebaExclusiva(){
            $resultado = $this->modelo->getPruebaExclusiva();
            return $resultado;
        }
        // Función para obtener las pruebas 4x100 existentes en la base de datos para ser mostradas en la vista
        public function getPrueba4x100(){
            $resultado = $this->modelo->getPrueba4x100();
            return $resultado;
        }
        // Función para exportar los datos de las inscripciones a pruebas 
        public function exportarInscripciones($pruebas){
            $resultado = $this->modelo->exportarInscripciones($pruebas);

            // var_dump($pruebas);
            $nombreZip = 'Inscripciones.zip';   // Nombre del archivo ZIP
            $zip = new ZipArchive();

            // Intenta abrir el archivo ZIP para escritura
            if ($zip->open($nombreZip, ZipArchive::CREATE) !== true) {
                throw new Exception('No se pudo crear el archivo ZIP');
            }

            if(isset($resultado[0])){
                // echo "4x100";
                $nombrePrueba = '4x100';
                // $idPrueba = intval($datosPruebas['idPrueba']);
                $resultado2 = $this->modelo->getDatos4x100();
                
                // Creo un nuevo objeto Spreadsheet
                $excel = new Spreadsheet();
                $excel->removeSheetByIndex(0);  // Elimina la hoja 'Worksheet'

                // Organizo los datos en hojas de cálculo separadas por categoría y sexo
                while($fila = $resultado2->fetch_assoc()){
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
            if(isset($resultado[1])){
                // echo "Exclusiva";
                foreach($pruebas as $prueba){
                    $result = $this->modelo->getDatosPrueba($prueba);
                    
                    while($datosPruebas = $result->fetch_assoc()){
                        $nombrePrueba = $datosPruebas['nombre'];
                        $idPrueba = intval($datosPruebas['idPrueba']);
                        
                        $resultado2 = $this->modelo->getDatosExclusivas($idPrueba);
                        
                        // Creo un nuevo objeto Spreadsheet
                        $excel = new Spreadsheet();
                        $excel->removeSheetByIndex(0);  // Elimina la hoja 'Worksheet'

                        // Organizo los datos en hojas de cálculo separadas por categoría y sexo
                        while($fila = $resultado2->fetch_assoc()){
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
        }

        //funciones pdf
        public function exportarInscripcionesPdf($pruebas){
            $resultado = $this->modelo->exportarInscripciones($pruebas);
            if(isset($resultado[0])){
                $nombrePrueba = '4x100';
                $resultado2 = $this->modelo->getDatos4x100();
                ob_clean();
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0,10,utf8_decode('Torneo Olímpico'),0,1,'C');
                $categoriaActual = null; // Inicializar la categoría actual
                $sexoActual = null; // Inicializar el sexo actual
                $i=1;
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
            if(isset($resultado[1])){
                foreach($pruebas as $prueba){
                    $result = $this->modelo->getDatosPrueba($prueba);
                    ob_clean();
                    $i=1;
                    while($datosPruebas = $result->fetch_assoc()){
                        $nombrePrueba = $datosPruebas['nombre'];
                        $idPrueba = intval($datosPruebas['idPrueba']);
                        
                        $resultado2 = $this->modelo->getDatosExclusivas($idPrueba);
                        $pdf = new FPDF();
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
                
            }
            ob_end_clean(); 
            readfile($zipFileName);

            // Elimina el archivo ZIP después de la descarga
            unlink($zipFileName);
        }
        public function generarPDFTodas(){
            $result = $this->modelo->todasPruebas();
            while ($row = $result->fetch_assoc()) {
                $nombrePrueba = $row['nombre']; // Obtener el nombre de la prueba
                // echo $nombrePrueba;
                
                if($nombrePrueba=='4x100'){
                    $resultado2 = $this->modelo->getDatos4x100();
                    ob_clean();
                    $pdf = new FPDF();
                    $pdf->AddPage();
                    $pdf->SetFont('Arial','B',16);
                    $pdf->Cell(0,10,utf8_decode('Torneo Olímpico'),0,1,'C');
                    $categoriaActual = null; // Inicializar la categoría actual
                    $sexoActual = null; // Inicializar el sexo actual
                    $i=1;
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
                }else{
                    $idsPruebas = array(); // Crear un array para almacenar los IDs de las pruebas
                
                    foreach($result as $datosPruebas) {
                        $idsPruebas[] = intval($datosPruebas['idPrueba']); // Almacenar el ID de la prueba en el array
                    }
                    array_shift($idsPruebas);
                    foreach($idsPruebas as $prueba){
                        $result = $this->modelo->getDatosPrueba($prueba);
                        $i=1;
                        while($datosPruebas = $result->fetch_assoc()){
                            $nombrePrueba = $datosPruebas['nombre'];
                            $idPrueba = intval($datosPruebas['idPrueba']);
                            $resultado2 = $this->modelo->getDatosExclusivas($idPrueba);
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