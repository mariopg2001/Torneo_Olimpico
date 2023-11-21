<?php
 session_start();
    include_once "cabecera.html";
   

    require_once('../controlador/controladorPrueba.php');
            $mensaje=0;
    $controlador = new ControladorPrueba;
    $pruebas = $controlador->pruebasyfilas();
    $alumnosmasculino = $controlador->alumno($_SESSION['usuario'],'m');
    echo '<main>
    <div class="p-4 mb-3 text-dark border border-dark titulo">
    <h5 class="text-center">Listado de Pruebas</h5>
</div>
<form method="post" action=./formularioInscripcion.php>
    ';

// Obtener los resultados de la consulta
$result = $pruebas[0];
$filas = $pruebas[1];

// Obtener los nombres de las pruebas y participantes
$nombresPruebas = array();
$numeroParticipantes = array();

while ($fila = $result->fetch_assoc()) {
$nombresPruebas[] = $fila['nombre'];
$numeroParticipantes[] = $fila['Max_Participantes'];
}

echo '<h4>Inscripciones Masculinas</h4><table class="inscripciones">';
for ($i = 0; $i < $filas; $i++) {
    echo '<tr>';
    if ($nombresPruebas[$i] == '4x100') {
        echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '.$numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
            echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-control relevos" onchange="removeOptionRelevos(this)">
            <option value="0" hidden>Elige un participante</option>';
            foreach($alumnosmasculino as $alumno){
                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
            }
            echo '</select></div>';
        }
        echo '</td></tr>';
    } else {

        echo '<tr><td><h5>' . $nombresPruebas[$i] . ' (Máximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
        $i++; // Avanzar al siguiente elemento
        if (isset($nombresPruebas[$i])) {
            echo '<td><h5>' . $nombresPruebas[$i] . ' (Maximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
        }
        echo '</tr><tr><td class="pruebasceldas">';
        $i--; // volver al elemento actual

        
        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class=" form-control exclusiva" onchange="removeOptionExlusiva(this)">
            <option value="0" hidden>Elige un participante</option>';
            foreach($alumnosmasculino as $alumno){
                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
            }
            echo '</select></div>';
        }
        echo '</td><td class="pruebasceldas">';
        $i++; 
        if (!isset($nombresPruebas[$i])) {
            echo '</td>';
        }else{
             for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-control exclusiva" onchange="removeOptionExlusiva(this)">
            <option value="0" hidden>Elige un participante</option>';
            foreach($alumnosmasculino as $alumno){
                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
            }
            echo '</select></div>';
        }
        echo '</td>';
        }
    }
}
echo '</table>';

$alumnosfemenino = $controlador->alumno($_SESSION['usuario'],'f');

echo '<h4>Inscripciones Femenino</h4><table class="inscripciones">';
for ($i = 0; $i < $filas; $i++) {
    echo '<tr>';
    if ($nombresPruebas[$i] == '4x100') {
        echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '. $numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
            echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-control relevos" onchange="removeOptionRelevos(this)">
            <option value="0" hidden>Elige un participante</option>';
            foreach($alumnosfemenino as $alumno){
                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
            }
            echo '</select></div>';
        }
        echo '</td></tr>';
    } else {

        echo '<tr><td><h5>' . $nombresPruebas[$i] . '(Minimo '. $numeroParticipantes[$i].' participantes)</h5></td>';
        $i++; // Avanzar al siguiente elemento
        if (isset($nombresPruebas[$i])) {
            echo '<td><h5>' . $nombresPruebas[$i] . '(Minimo '. $numeroParticipantes[$i].' participantes)</h5></td>';
        }
        echo '</tr><tr><td class="pruebasceldas">';
        $i--; // volver al elemento actual

        
        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-control exclusiva" onchange="removeOptionExlusiva(this)">
            <option value="0" hidden>Elige un participante</option>';
            foreach($alumnosfemenino as $alumno){
                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
            }
            echo '</select></div>';
        }
        echo '</td><td class="pruebasceldas">';
        $i++; 
        if (!isset($nombresPruebas[$i])) {
            echo '</td>';
        }else{
             for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-control exclusiva" onchange="removeOptionExlusiva(this)">
            <option value="0" hidden>Elige un participante</option>';
            foreach($alumnosfemenino as $alumno){
                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
            }
            echo '</select></div>';
        }
        echo '</td>';
        }
    }
}
echo '</table>';
if($mensaje==1){
    echo '<br/><h5>El formulario debe estar relleno con los elementos minimos</h5>';
}
echo '<br><br>
        <div class="p-5 col-auto text-center">
        <a href="./indexPrueba.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></a>
        <button type="submit" name="guardar" class="btn btn-primary btn-submit">Guardar</button>
        </div></div><br>
  </form></main>';
include_once 'footer.html';
if(isset($_POST['guardar'])){
    require_once('../controlador/controladorPrueba.php');

    
    $result = $controlador->pruebasyfilas();
    $nombrepruebaparticipantes = array();
   $idclase= $controlador->clase($_SESSION['usuario']);
    if(!empty($_POST)){ 
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'participante') !== false) {
                $prueba = substr($key, 0, strpos($key, 'participante'));
                $letra = substr($key, -1); // Obtener la letra después de "participante"
                if (!isset($nombrepruebaparticipantes[$prueba])) {
                    $nombrepruebaparticipantes[$prueba] = array();
                }
                // echo $prueba.'<br/>';
                if($value!=0){
                    
                    $nombrepruebaparticipantes[$prueba][] = array('letra' => $letra, 'nombrePrueba' => $prueba, 'idAlumno' => $value,'clase'=>$idclase);
                }
            }
        }
        
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'participante') !== false) {
                $prueba = substr($key, 0, strpos($key, 'participante'));
                if (empty($nombrepruebaparticipantes[$prueba]) ) {
                    unset($nombrepruebaparticipantes[$prueba]);
                }
            }
        }
          $participantefemenino=0;
            $participantemasculino=0;
            if(!isset($nombrepruebaparticipantes['4x100'])){
               echo '';
            }else{
                for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                    if($nombrepruebaparticipantes['4x100'][$i]['letra']=="m"){
                        $participantemasculino++;
                    }else{
                        $participantefemenino++;
                    }
                }
               
                if($participantefemenino<4){
                    for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                        if($nombrepruebaparticipantes['4x100'][$i]['letra']=="f"){
                            unset($nombrepruebaparticipantes['4x100'][$i]);
                        }

                    }
                }
                if($participantemasculino<4){
                    for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                        if($nombrepruebaparticipantes['4x100'][$i]['letra']=="m"){
                            unset($nombrepruebaparticipantes['4x100'][$i]);
                        }

                    }
                }
                if(count($nombrepruebaparticipantes['4x100'])==0){
                    unset($nombrepruebaparticipantes['4x100']);
                }
            }
            
           if(count($nombrepruebaparticipantes)==0){
            unset($nombrepruebaparticipantes);
            echo '<h5>Debe rellenar alguna prueba para enviar el formulario la prueba de relevos tienen que estar los 4 alumnos seleccionados</h5><br/><br/>';
            
           }else{
            $pruebas = $controlador->altainscripcion($nombrepruebaparticipantes);
           
          
           }
           
        }else{
            echo 'debe rellenar el formulario con los elementos minimos';
    }
}
