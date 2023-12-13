<?php
require_once('../controlador/controladorPrueba.php');
    $controlador= new ControladorPrueba;
 $datos=$controlador->consultaPrueba4x100();

 require_once('../fpdf/fpdf.php');
 
 class PDF extends FPDF {
     function Header() {
         $this->SetFont('Arial','B',15);
         $this->Cell(0,10,'Torneo Olimpico',0,1,'C');
     }
 }
 
 $pdf = new PDF();
 $pdf->AddPage();
 $pdf->SetFont('Arial','',9);
 $pdf->SetX(15);
 
 $categoriaActual = null; // Inicializar la categoría actual
 $sexoActual = null; // Inicializar el sexo actual
 
 
 
 foreach($datos as $linea){
     $categoria = $linea['nombreCategoria'];
     $sexo = $linea['sexo'];
 
     if ($categoria != $categoriaActual || $sexo != $sexoActual) {
         if ($categoriaActual !== null) {
             $pdf->AddPage();
             // Agregar encabezado de la tabla en la nueva página
           
         }
         $categoriaActual = $categoria;
         $sexoActual = $sexo;
         if($sexo=='m'){
             $sexo2='Masculino';
         }else{
             $sexo2='Femenino';
         }
         $pdf->SetFont('Arial','B',10);
         $pdf->Cell(0,10,'Prueba 4x100 Cat: '. $categoria.' Sexo:'. $sexo2,0,1,'C');
         $pdf->Ln(20);
     $pdf->SetX(65); // Ajustar la posición X para el contenido de la tabla
 
         $pdf->Cell(40, 10, 'Nombre del Alumno', 1, 0, 'C');
         $pdf->Cell(40, 10, 'Clase', 1, 1, 'C');
     }
     $pdf->SetX(65); // Ajustar la posición X para el contenido de la tabla
 
     $nombre = $linea['nombreAlumno'];
     $seccion = $linea['nombreSeccion'];
     $pdf->SetFont('Arial','',9);
     $pdf->Cell(40,10,utf8_decode($nombre), 1, 0, 'C');
     $pdf->Cell(40,10,utf8_decode($seccion), 1, 1, 'C');
 }
 $pdf->Output("Inscripciones.pdf", "F");//nos descarga el archivo

?>