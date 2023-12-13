<?php
 // Iniciar la sesión
session_start();

// Verificar si el usuario no ha iniciado sesión
if(!isset($_SESSION['usuario'])|| $_SESSION['tipoUsuario']!='Tutor'){
    include_once "error.html"; // Incluir la página de error
}else{
    // Incluir el archivo "cabecera.html"
    include_once "cabecera.html";

    // Requerir el controlador de prueba
    require_once('../controlador/controladorPrueba.php');
    $mensaje=0;
    $controlador = new ControladorPrueba;
    $pruebas = $controlador->pruebasyfilas();
    $fechaActual = date('Y-m-d');

    $fechasInscripcion=$controlador->fechasInscripcion(); //consultar las fechas de las inscripciones
    if($fechaActual<=$fechasInscripcion[0]){
        echo ' <div class="p-4 mb-3 text-dark border border-dark titulo">
        <h5 class="text-center">Listado de Pruebas</h5>
        </div>
        <h5>El proceso de inscripcion no ha empezado</h5>';
    }else{
        if($fechaActual>=$fechasInscripcion[1]){
            
            echo '
            <div class="p-4 mb-3 text-dark border border-dark titulo">
            <h5 class="text-center">Listado de Pruebas</h5>
            </div>
        <h5>El proceso de inscripcion ya ha terminado</h5>';

        }else{
            $participantes4x100=$controlador->participantes4x100($_SESSION['usuario']);
    $alumnosmasculino = $controlador->alumno($_SESSION['usuario'],'m');
    echo '<main>
    <div class="p-4 mb-3 text-dark border border-dark titulo">
    <h5 class="text-center">Listado de Pruebas</h5>
    </div>
    <form method="post" action=./formularioInscripcion.php>';

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
    $participaMasc4x100=array();
    $participafem4x100=array();
        foreach($participantes4x100[0] as $fila){
            if($fila['sexo']=='m'){
            $participaMasc4x100[1]=$fila['participante1'];
            $participaMasc4x100[2]=$fila['participante2'];
            $participaMasc4x100[3]=$fila['participante3'];
            $participaMasc4x100[4]=$fila['participante4'];
                
            }
            else{
                $participafem4x100[1]=$fila['participante1'];
                $participafem4x100[2]=$fila['participante2'];
                $participafem4x100[3]=$fila['participante3'];
                $participafem4x100[4]=$fila['participante4'];
            }
        }
        echo '<h4>Inscripciones Masculinas</h4><table class="inscripciones">';
        for ($i = 0; $i < $filas; $i++) {
            echo '<tr>';
            //comprobar que la prueba es 4x100
            if ($nombresPruebas[$i] == '4x100') {
                // Verificar si hay participantes en la prueba 4x100 y si hay participantes masculinos
                if($participantes4x100[1]>0 && count($participaMasc4x100)!=0){
                    // Mostrar el nombre de la prueba y el número mínimo de participantes
                    echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '.$numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                    // Iterar sobre el número de participantes y crear un select para cada uno
                    for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                        echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select relevos" onchange="removeOptionRelevos(this)">
                        <option value="0">Elige un participante</option>';
                        // Iterar sobre los alumnos masculinos y mostrar opciones en el select
                        foreach($alumnosmasculino as $alumno){
                            if( $participaMasc4x100[$x]==$alumno['idAlumno']){
                                echo '<option value="'.$alumno['idAlumno'].'" selected>'.$alumno['nombre'].'</option>';
                            }else{
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                        }
                        echo '</select></div>';
                    }
                    echo '</td></tr>';
                }else{
                        // Mostrar el nombre de la prueba y el número mínimo de participantes
                        echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '.$numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                        // Iterar sobre el número de participantes y crear un select para cada uno
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                            echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select relevos" onchange="removeOptionRelevos(this)">
                            <option value="0">Elige un participante</option>';
                            // Iterar sobre los alumnos masculinos y mostrar opciones en el select
                            foreach($alumnosmasculino as $alumno){
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                            echo '</select></div>';
                        }
                        echo '</td></tr>';
                    }
            
            } else {
                // Obtener participantes exclusivos para la prueba actual
                $participantesExclusiva=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);

                // Mostrar el nombre de la prueba y el número máximo de participantes
                echo '<tr><td><h5>' . $nombresPruebas[$i] . ' (Máximo '.$numeroParticipantes[$i].' participantes)</h5></td>';

                // Avanzar al siguiente elemento
                $i++;

                // Verificar si hay otra prueba disponible
                if (isset($nombresPruebas[$i])) {
                    // Obtener participantes exclusivos para la siguiente prueba
                    $participantesExclusiva2=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);
                    // Mostrar el nombre de la siguiente prueba y el número máximo de participantes
                    echo '<td><h5>' . $nombresPruebas[$i] . ' (Maximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
                }

                echo '</tr><tr><td class="pruebasceldas">';

                // Volver al elemento actual
                $i--;
                
                // Verificar si hay participantes exclusivos para la prueba actual
                if($participantesExclusiva[1]>0){
                    $m=1;
                    $alumnos3=array();
                    // Iterar sobre los participantes exclusivos y guardar los masculinos en un array
                    foreach($participantesExclusiva[0] as $fila3){
                        if($fila3['sexo']=='m'){
                            $alumnos3[$m]=$fila3['idAlumno'];
                            $m++;
                        }
                    }
                    // Comprobar si el número de participantes masculinos es menor que el número mínimo requerido
                    if(count($alumnos3)<$numeroParticipantes[$i]){
                        // Completar con participantes faltantes
                        for($h=1;$h < ($numeroParticipantes[$i])-count($alumnos3);$h++){
                            $alumnos3[$m]=0;
                            $m++;
                        }
                    }
                        
                    // Iterar sobre el número de participantes y crear un select para cada uno
                    for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                        echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                        ';
                        if($alumnos3[$x]==0){
                            echo '<option value="0">Elige un participante</option>';
                            foreach($alumnosmasculino as $alumno){
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                        }else{
                            foreach($alumnosmasculino as $alumno){
                                if($alumnos3[$x]==$alumno['idAlumno']){
                                    echo '<option value="0">Elige un participante</option>';
                                    echo '<option value="'.$alumno['idAlumno'].'" selected>'.$alumno['nombre'].'</option>';
                                }else{
                                    echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                                }    
                            }
                        } 
                        echo '</select></div>';
                        unset($alumnos);
                    }
                }else{
                    // Iterar sobre el número de participantes y crear un select para cada uno
                    for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                        echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                        <option value="0">Elige un participante</option>';
                        foreach($alumnosmasculino as $alumno){
                            echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                        }
                        echo '</select></div>';
                    }
                }
                echo '</td><td class="pruebasceldas">';
                    // Avanzar al siguiente elemento
                $i++; 
                // Verificar si no existe el nombre de la prueba en la posición $i y cerrar la etiqueta </td>
                if (!isset($nombresPruebas[$i])) {
                    echo '</td>';
                }else{
                    $alumnos=array();
                    // Verificar si hay participantes exclusivos para la siguiente prueba
                    if($participantesExclusiva2[1]>0){
                        $m=1;
                        $alumnos3=array();
                        // Iterar sobre los participantes exclusivos y guardar los masculinos en un array
                        foreach($participantesExclusiva2[0] as $fila3){
                            if($fila3['sexo']=='m'){
                                $alumnos3[$m]=$fila3['idAlumno'];
                                $m++;
                            }
                        }
                        // var_dump($alumnos3);
                       // Comprobar si el número de participantes masculinos es menor que el número mínimo requerido
                        if(count($alumnos3)<$numeroParticipantes[$i]){
                            // Completar con participantes faltantes
                            for($h=1;$h < ($numeroParticipantes[$i])-count($alumnos3);$h++){
                                $alumnos3[$m]=0;
                                $m++;
                            }
                        }
                        
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                            ';
                            // Comprobar si el participante ya está seleccionado o no
                            if($alumnos3[$x]==0){
                                echo '<option value="0">Elige un participante</option>';
                                foreach($alumnosmasculino as $alumno){
                                    echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                                }
                            }else{
                                foreach($alumnosmasculino as $alumno){
                                    // Si el participante ya está seleccionado, marcarlo como seleccionado
                                    if($alumnos3[$x]==$alumno['idAlumno']){
                                        echo '<option value="0">Elige un participante</option>';
                                        echo '<option value="'.$alumno['idAlumno'].'" selected>'.$alumno['nombre'].'</option>';
                                    }else{
                                        echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                                    }    
                                }
                            }  
                            echo '</select></div>';    
                            unset($alumnos);
                        }
                    }else{
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                            // Crear un div con la clase "pruebas" y un select con nombre dinámico
                            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                            <option value="0">Elige un participante</option>';
                            // Iterar sobre la lista de alumnos masculinos y crear opciones en el select
                            foreach($alumnosmasculino as $alumno){
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                            echo '</select></div>';
                        }
                    }
                    echo '</td>';
                }
            }
        }
        echo '</table>';
        // Obtener la lista de alumnos femeninos 
        $alumnosfemenino = $controlador->alumno($_SESSION['usuario'],'f');
        
        echo '<h4>Inscripciones Femenino</h4><table class="inscripciones">';
        for ($i = 0; $i < $filas; $i++) {
            echo '<tr>';
            //comprobar que la prueba es 4x100
            if ($nombresPruebas[$i] == '4x100') {
                // Verificar si hay participantes en la prueba 4x100 y si hay participantes femeninos

                if($participantes4x100[1]>0 && count($participafem4x100)!=0){
                    // Mostrar el nombre de la prueba y el número mínimo de participantes
                    echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '.$numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                    // Iterar sobre el número de participantes y crear un select para cada uno
                    for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                        echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select relevos" onchange="removeOptionRelevos(this)">
                        <option value="0">Elige un participante</option>';
                        // Iterar sobre los alumnos masculinos y mostrar opciones en el select
                        foreach($alumnosfemenino as $alumno){
                            if( $participafem4x100[$x]==$alumno['idAlumno']){
                                echo '<option value="'.$alumno['idAlumno'].'" selected>'.$alumno['nombre'].'</option>';
                            }else{
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                        }
                        echo '</select></div>';
                    }
                    echo '</td></tr>';
                }else{
                    // Mostrar el nombre de la prueba y el número mínimo de participantes
                    echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '. $numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                    // Iterar sobre el número de participantes y crear un select para cada uno   
                    for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                        echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select relevos" onchange="removeOptionRelevos(this)">
                        <option value="0">Elige un participante</option>';
                        // Iterar sobre los alumnos masculinos y mostrar opciones en el select    
                        foreach($alumnosfemenino as $alumno){
                            echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                        }
                        echo '</select></div>';
                    }
                    echo '</td></tr>';
                }
            } else {
                // Obtener participantes exclusivos para la prueba actual
                $participantesExclusiva=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);
                // Mostrar el nombre de la prueba y el número máximo de participantes
                echo '<tr><td><h5>' . $nombresPruebas[$i] . ' (Máximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
                $i++; // Avanzar al siguiente elemento
            
                // Verificar si hay otra prueba disponible
                if (isset($nombresPruebas[$i])) {
                    // Obtener participantes exclusivos para la siguiente prueba
                    $participantesExclusiva2=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);
                    // Mostrar el nombre de la siguiente prueba y el número máximo de participantes
                    echo '<td><h5>' . $nombresPruebas[$i] . ' (Maximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
                }
                
                echo '</tr><tr><td class="pruebasceldas">';
                
                $i--; // volver al elemento actual
                // Verificar si hay participantes exclusivos para la prueba actual
                if($participantesExclusiva[1]>0){
                    $m=1;
                    $alumnos3=array();
                    // Iterar sobre los participantes exclusivos y guardar los masculinos en un array
                    foreach($participantesExclusiva[0] as $fila3){
                        if($fila3['sexo']=='f'){
                        $alumnos3[$m]=$fila3['idAlumno'];
                        $m++;
                        }
                        }
                        // var_dump($alumnos3);
                        // Comprobar si el número de participantes femeninos es menor que el número mínimo requerido
                        if(count($alumnos3)<$numeroParticipantes[$i]){
                            // echo 'falta '.($numeroParticipantes[$i])-count($alumnos).'participante';
                            for($h=1;$h < ($numeroParticipantes[$i])-count($alumnos3);$h++){
                              // Completar con participantes faltantes  
                                $alumnos3[$m]=0;
                                $m++;
                                
                            }
                        }
                        
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                            ';
                            // Verificar si el participante actual es 0 y mostrar las opciones correspondientes
                            if($alumnos3[$x]==0){
                                echo '<option value="0">Elige un participante</option>';
                                // Iterar sobre la lista de alumnos femeninos y crear opciones en el select
                                foreach($alumnosfemenino as $alumno){
                                    echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                                }
                            }else{
                                echo '<option value="0">Elige un participante</option>';
                                // Iterar sobre la lista de alumnos femeninos y seleccionar el participante correspondiente
                                foreach($alumnosfemenino as $alumno){
                                    if($alumnos3[$x]==$alumno['idAlumno']){
                                        echo '<option value="'.$alumno['idAlumno'].'" selected>'.$alumno['nombre'].'</option>';
                                    }else{
                                        echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                                    }    
                                }
                            }
                            echo '</select></div>';
                            unset($alumnos);
                        }
                    }else{
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                            // Crear un div con la clase "pruebas" y un select con nombre dinámico
                            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                            <option value="0">Elige un participante</option>';
                            // Iterar sobre la lista de alumnos femeninos y crear opciones en el select
                            foreach($alumnosfemenino as $alumno){
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                            echo '</select></div>';
                        }
                    }
                echo '</td><td class="pruebasceldas">';
                $i++; // Avanzar al siguiente elemento
                // Verificar si no existe el nombre de la prueba en la posición $i y cerrar la etiqueta </td>
                if (!isset($nombresPruebas[$i])) {
                    echo '</td>';
                }else{
                    $alumnos=array();
                    if($participantesExclusiva2[1]>0){
                        $m=1;
                        $alumnos3=array();
                        foreach($participantesExclusiva2[0] as $fila3){
                            if($fila3['sexo']=='f'){
                                $alumnos3[$m]=$fila3['idAlumno'];
                                $m++;
                            }
                        }
                        // var_dump($alumnos3);
                        if(count($alumnos3)<$numeroParticipantes[$i]){
                            // echo 'falta '.($numeroParticipantes[$i])-count($alumnos).'participante';
                            for($h=1;$h < ($numeroParticipantes[$i])-count($alumnos3);$h++){
                                $alumnos3[$m]=0;
                                $m++;
                            }
                        }
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                            ';
                            // Verificar si el participante actual es 0 y mostrar las opciones correspondientes
                            if($alumnos3[$x]==0){
                                echo '<option value="0">Elige un participante</option>';
                                // Iterar sobre la lista de alumnos femeninos y crear opciones en el select
                                foreach($alumnosfemenino as $alumno){
                                    echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                                }
                            }else{
                                // Iterar sobre la lista de alumnos femeninos y seleccionar el participante correspondiente
                                foreach($alumnosfemenino as $alumno){
                                    if($alumnos3[$x]==$alumno['idAlumno']){
                                        echo '<option value="0">Elige un participante</option>';
                                        echo '<option value="'.$alumno['idAlumno'].'" selected>'.$alumno['nombre'].'</option>';
                                    }else{
                                        echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                                    }    
                                }
                            }
                            echo '</select></div>';
                            unset($alumnos);
                        }
                    }else{
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                            // Crear un div con la clase "pruebas" y un select con nombre dinámico
                            echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                            <option value="0">Elige un participante</option>';
                            // Iterar sobre la lista de alumnos femeninos y crear opciones en el select
                            foreach($alumnosfemenino as $alumno){
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                            echo '</select></div>';
                        }
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
                <a href="./indexPrueba.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button></a>
                <button type="submit" name="guardar" class="btn btn-primary btn-submit">Guardar</button>
                </div></div><br>
        </form></main>';
        include_once 'footer.html';
        }
        if(isset($_POST['guardar'])){
            require_once('../controlador/controladorPrueba.php');
            $idclase= $controlador->clase($_SESSION['usuario']);
            $inscripciones=$controlador->consultaInscripciones($idclase);
            
            if($inscripciones>0){
                $controlador->BorrarInscripcionesdeClase($idclase);
                $result = $controlador->pruebasyfilas();
                $nombrepruebaparticipantes = array();
                //si no esta vacio $post
                if(!empty($_POST)){ 
                    // Itera a través de los datos POST para extraer información sobre los participantes en una prueba específica
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, 'participante') !== false) {
                            $prueba = substr($key, 0, strpos($key, 'participante'));
                            $letra = substr($key, -1); // Obtiene la letra después de "participante"
                            if (!isset($nombrepruebaparticipantes[$prueba])) {
                                $nombrepruebaparticipantes[$prueba] = array();
                            }
                            // Agrega la información del participante a la prueba correspondiente si el valor no es 0
                            if($value!=0){
                                $nombrepruebaparticipantes[$prueba][] = array('letra' => $letra, 'nombrePrueba' => $prueba, 'idAlumno' => $value,'clase'=>$idclase);
                            }
                        }
                    }
                    
                  // Itera a través de los datos POST para buscar claves que contengan "participante"
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, 'participante') !== false) {
                            // Extrae el nombre de la prueba de la clave
                            $prueba = substr($key, 0, strpos($key, 'participante'));
                            // Elimina la entrada del array "nombrepruebaparticipantes" si está vacía
                            if (empty($nombrepruebaparticipantes[$prueba]) ) {
                                unset($nombrepruebaparticipantes[$prueba]);
                            }
                        }
                    }
                    $participantefemenino=0; // Inicializa el contador de participantes femeninos
                    $participantemasculino=0; // Inicializa el contador de participantes masculinos
                    
                    // Verifica si la prueba '4x100' está definida en el array 'nombrepruebaparticipantes'
                    if(!isset($nombrepruebaparticipantes['4x100'])){
                        echo ''; // No hace nada si la prueba no está definida
                    }else{
                        // Itera a través de los participantes de la prueba '4x100' para contar los masculinos y femeninos
                        for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                            if($nombrepruebaparticipantes['4x100'][$i]['letra']=="m"){
                                $participantemasculino++; // Incrementa el contador de participantes masculinos
                            }else{
                                $participantefemenino++; // Incrementa el contador de participantes femeninos
                            }
                        }
                    
                        // Verifica si hay menos de 4 participantes femeninos en la prueba '4x100'
                        if($participantefemenino<4){
                            // Muestra una alerta si faltan participantes femeninos en la inscripción de la prueba 4x100
                            if($participantefemenino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 femenina no se ha podido realizar por que faltaban participantes");</script>';            
                            }
                            // Elimina los participantes masculinos de la prueba 4x100 si faltan participantes femeninos
                            for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                                if($nombrepruebaparticipantes['4x100'][$i]['letra']=="f"){
                                    unset($nombrepruebaparticipantes['4x100'][$i]);
                                }
                            }
                        }
                    
                        // Verifica si hay menos de 4 participantes masculinos en la prueba '4x100'
                        if($participantemasculino<4){
                            // Muestra una alerta si faltan participantes masculinos en la inscripción de la prueba 4x100
                            if($participantemasculino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 masculino no se ha podido realizar por que faltaban participantes");</script>';            
                            }
                            // Elimina los participantes femeninos de la prueba 4x100 si faltan participantes masculinos
                            for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                                if($nombrepruebaparticipantes['4x100'][$i]['letra']=="m"){
                                    unset($nombrepruebaparticipantes['4x100'][$i]);
                                }
                            }
                        }
                    
                        // Elimina la prueba '4x100' del array 'nombrepruebaparticipantes' si no tiene participantes
                        if(count($nombrepruebaparticipantes['4x100'])==0){
                            unset($nombrepruebaparticipantes['4x100']);
                        }
                    }
                        
                    // Verifica si no hay pruebas seleccionadas en el array 'nombrepruebaparticipantes'
                    if(count($nombrepruebaparticipantes)==0){
                        unset($nombrepruebaparticipantes); // Elimina el array 'nombrepruebaparticipantes'
                        echo '<h5>Debe rellenar alguna prueba para enviar el formulario la prueba de relevos tienen que estar los 4 alumnos seleccionados</h5><br/><br/>'; // Muestra un mensaje de alerta
                    }else{
                        $pruebas = $controlador->altainscripcion($nombrepruebaparticipantes); // Realiza la inscripción de las pruebas
                        $mensaje="Las inscripciones se han modificado correctamente"; // Establece un mensaje de éxito
                        echo '<script>window.location.href = "./indexPrueba.php?mensaje2='.$mensaje.'";</script>'; // Redirige a la página de índice con el mensaje de éxito
                    }
                    // Si no se cumple la condición anterior, muestra un mensaje indicando que se deben rellenar los elementos mínimos del formulario
                }else{
                        echo 'debe rellenar el formulario con los elementos minimos';
                    }
                }else{
                $result = $controlador->pruebasyfilas(); // Llama al método 'pruebasyfilas' del controlador
                $nombrepruebaparticipantes = array(); // Inicializa el array 'nombrepruebaparticipantes'
                
                // Verifica si el array POST no está vacío
                if(!empty($_POST)){ 
                    // Itera a través de los elementos del array POST
                    foreach ($_POST as $key => $value) {
                        // Verifica si la clave contiene 'participante'
                        if (strpos($key, 'participante') !== false) {
                            $prueba = substr($key, 0, strpos($key, 'participante')); // Obtiene el nombre de la prueba
                            $letra = substr($key, -1); // Obtiene la letra después de "participante"
                            if (!isset($nombrepruebaparticipantes[$prueba])) {
                                $nombrepruebaparticipantes[$prueba] = array(); // Inicializa un array para la prueba si no existe
                            }
                            if($value!=0){
                                // Agrega un nuevo participante al array de la prueba
                                $nombrepruebaparticipantes[$prueba][] = array('letra' => $letra, 'nombrePrueba' => $prueba, 'idAlumno' => $value,'clase'=>$idclase);
                            }
                        }
                    }
                    
                    // Itera a través de los elementos del array POST nuevamente
                    foreach ($_POST as $key => $value) {
                        // Verifica si la clave contiene 'participante'
                        if (strpos($key, 'participante') !== false) {
                            $prueba = substr($key, 0, strpos($key, 'participante')); // Obtiene el nombre de la prueba
                            if (empty($nombrepruebaparticipantes[$prueba]) ) {
                                unset($nombrepruebaparticipantes[$prueba]); // Elimina la prueba si no tiene participantes
                            }
                        }
                    }
                    $participantefemenino=0; // Inicializa el contador de participantes femeninos
                    $participantemasculino=0; // Inicializa el contador de participantes masculinos
                    
                    // Verifica si la prueba '4x100' no está establecida en el array 'nombrepruebaparticipantes'
                    if(!isset($nombrepruebaparticipantes['4x100'])){
                        echo ''; // No hace nada si la prueba no está establecida
                    }else{
                        // Itera a través de los participantes de la prueba '4x100'
                        for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                            // Verifica si el participante es masculino o femenino y actualiza los contadores
                            if($nombrepruebaparticipantes['4x100'][$i]['letra']=="m"){
                                $participantemasculino++;
                            }else{
                                $participantefemenino++;
                            }
                        }
                    
                        // Verifica si el número de participantes femeninos es menor que 4
                        if($participantefemenino<4){
                            // Muestra una alerta si faltan participantes femeninos en la prueba 4x100
                            if($participantefemenino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 femenina no se ha podido realizar por que faltaban participantes");</script>';            
                            }
                            // Elimina los participantes femeninos de la prueba 4x100
                            for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                                if($nombrepruebaparticipantes['4x100'][$i]['letra']=="f"){
                                    unset($nombrepruebaparticipantes['4x100'][$i]);
                                }
                            }
                        }
                    
                        // Verifica si el número de participantes masculinos es menor que 4
                        if($participantemasculino<4){
                            // Muestra una alerta si faltan participantes masculinos en la prueba 4x100
                            if($participantemasculino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 masculina no se ha podido realizar por que faltaban participantes");</script>';            
                            }
                            // Elimina los participantes masculinos de la prueba 4x100
                            for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                                if($nombrepruebaparticipantes['4x100'][$i]['letra']=="m"){
                                    unset($nombrepruebaparticipantes['4x100'][$i]);
                                }
                            }
                        }
                    
                        // Verifica si no hay participantes restantes en la prueba 4x100 y la elimina del array
                        if(count($nombrepruebaparticipantes['4x100'])==0){
                            unset($nombrepruebaparticipantes['4x100']);
                        }
                    }
                        
                    // Verifica si no hay pruebas seleccionadas en el array 'nombrepruebaparticipantes'
                    if(count($nombrepruebaparticipantes)==0){
                        unset($nombrepruebaparticipantes); // Elimina el array 'nombrepruebaparticipantes'
                        echo '<h5>Debe rellenar alguna prueba para enviar el formulario la prueba de relevos tienen que estar los 4 alumnos seleccionados</h5><br/><br/>'; // Muestra un mensaje de alerta
                    }else{
                        $pruebas = $controlador->altainscripcion($nombrepruebaparticipantes); // Llama al método 'altainscripcion' del controlador con el array 'nombrepruebaparticipantes'
                        $mensaje="Las inscripciones se han creado correctamente"; // Establece el mensaje de éxito
                        echo '<script>window.location.href = "./indexPrueba.php?mensaje2='.$mensaje2.'";</script>'; // Redirige a la página 'indexPrueba.php' con el mensaje de éxito
                    }
                    
                    }else{
                        echo 'debe rellenar el formulario con los elementos minimos';
                    }
            }       
        }
    }
}