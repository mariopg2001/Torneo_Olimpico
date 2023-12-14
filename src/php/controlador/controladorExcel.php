<?php
    require_once '../modelo/modeloExcel.php';
    require '../../../vendor/autoload.php';
    
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
    }