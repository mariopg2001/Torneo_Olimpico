<?php
 session_start();
 if(!isset($_SESSION['usuario'])){
    include_once "error.html";
}else{
    include_once "cabecera.html";
   

    require_once('../controlador/controladorPrueba.php');
            $mensaje=0;
    $controlador = new ControladorPrueba;
    $pruebas = $controlador->pruebasyfilas();
    $fechaActual = date('Y-m-d');

    $fechasInscripcion=$controlador->fechasInscripcion();
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
        
        if ($nombresPruebas[$i] == '4x100') {
            if($participantes4x100[1]>0 && count($participaMasc4x100)!=0){
                
    
                echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '.$numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                    echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select relevos" onchange="removeOptionRelevos(this)">
                    <option value="0">Elige un participante</option>';
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
    
                echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '.$numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                    echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select relevos" onchange="removeOptionRelevos(this)">
                    <option value="0">Elige un participante</option>';
                    foreach($alumnosmasculino as $alumno){
                        echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                    }
                    echo '</select></div>';
                }
                echo '</td></tr>';
            }
           
        } else {
            $participantesExclusiva=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);
           
            echo '<tr><td><h5>' . $nombresPruebas[$i] . ' (Máximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
            $i++; // Avanzar al siguiente elemento
           
            if (isset($nombresPruebas[$i])) {
                $participantesExclusiva2=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);
                
                
                echo '<td><h5>' . $nombresPruebas[$i] . ' (Maximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
            }
              
            echo '</tr><tr><td class="pruebasceldas">';
            
            $i--; // volver al elemento actual
            
           if($participantesExclusiva[1]>0){
                  $m=1;
                  $alumnos3=array();
                  foreach($participantesExclusiva[0] as $fila3){
                    if($fila3['sexo']=='m'){
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
            $i++; 
            if (!isset($nombresPruebas[$i])) {
                echo '</td>';
            }else{
                $alumnos=array();
                if($participantesExclusiva2[1]>0){
                    $m=1;
                    $alumnos3=array();
                    foreach($participantesExclusiva2[0] as $fila3){
                      if($fila3['sexo']=='m'){
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
                        for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'m" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                <option value="0">Elige un participante</option>';
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
    
    $alumnosfemenino = $controlador->alumno($_SESSION['usuario'],'f');
    
    echo '<h4>Inscripciones Femenino</h4><table class="inscripciones">';
    for ($i = 0; $i < $filas; $i++) {
        echo '<tr>';
        if ($nombresPruebas[$i] == '4x100') {
            if($participantes4x100[1]>0 && count($participafem4x100)!=0){
    
                echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '.$numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                    echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select relevos" onchange="removeOptionRelevos(this)">
                    <option value="0">Elige un participante</option>';
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
                echo '<td colspan="2"><h5>' . $nombresPruebas[$i] . ' (Minimo '. $numeroParticipantes[$i].' participantes)</h5></td></tr><td colspan="2">';
                for( $x=1; $x<=$numeroParticipantes[$i]; $x++){
                    echo'<div class="relevos"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select relevos" onchange="removeOptionRelevos(this)">
                    <option value="0">Elige un participante</option>';
                    foreach($alumnosfemenino as $alumno){
                        echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                    }
                    echo '</select></div>';
                }
                echo '</td></tr>';
            }
           
        } else {
            $participantesExclusiva=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);
           
            echo '<tr><td><h5>' . $nombresPruebas[$i] . ' (Máximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
            $i++; // Avanzar al siguiente elemento
           
            if (isset($nombresPruebas[$i])) {
                $participantesExclusiva2=$controlador->participantesExclusiva($nombresPruebas[$i],$_SESSION['usuario']);
                
                
                echo '<td><h5>' . $nombresPruebas[$i] . ' (Maximo '.$numeroParticipantes[$i].' participantes)</h5></td>';
            }
              
            echo '</tr><tr><td class="pruebasceldas">';
            
            $i--; // volver al elemento actual
            
           if($participantesExclusiva[1]>0){
                  $m=1;
                  $alumnos3=array();
                  foreach($participantesExclusiva[0] as $fila3){
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
                        if($alumnos3[$x]==0){
                            echo '<option value="0">Elige un participante</option>';
                            foreach($alumnosfemenino as $alumno){
                               
                                echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                            }
                        }else{
                            echo '<option value="0">Elige un participante</option>';
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
                echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                <option value="0">Elige un participante</option>';
                foreach($alumnosfemenino as $alumno){
                    echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                }
                echo '</select></div>';
                }
             
            }
            echo '</td><td class="pruebasceldas">';
            $i++; 
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
                          if($alumnos3[$x]==0){
                              echo '<option value="0">Elige un participante</option>';
                              foreach($alumnosfemenino as $alumno){
                                 
                                  echo '<option value="'.$alumno['idAlumno'].'">'.$alumno['nombre'].'</option>';
                              }
                          }else{
                             
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
                echo'<div class="pruebas"><select name="'.$nombresPruebas[$i].'participante'.$x.'f" class="form-select exclusiva" onchange="removeOptionExlusiva(this)">
                <option value="0">Elige un participante</option>';
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
            <a href="./indexPrueba.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></a>
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
                            if($participantefemenino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 femenina no se ha podido realizar por que faltaban participantes");</script>';            
                            }
                            for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                                if($nombrepruebaparticipantes['4x100'][$i]['letra']=="f"){
                                    unset($nombrepruebaparticipantes['4x100'][$i]);
                                }
        
                            }
                        }
                        if($participantemasculino<4){
                            if($participantemasculino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 masculino no se ha podido realizar por que faltaban participantes");</script>';            
                            }
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
                    $mensaje="Las inscripciones se han modificado correctamente";
                    echo '<script>window.location.href = "./indexPrueba.php?mensaje='.$mensaje.'";</script>';
                   
                  
                   }
                   
                }else{
                    echo 'debe rellenar el formulario con los elementos minimos';
            }
        }else{
            $result = $controlador->pruebasyfilas();
            $nombrepruebaparticipantes = array();
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
                            if($participantefemenino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 femenina no se ha podido realizar por que faltaban participantes");</script>';            
                            }
                            for($i=0;$i<count($nombrepruebaparticipantes['4x100']);$i++){
                                if($nombrepruebaparticipantes['4x100'][$i]['letra']=="f"){
                                    unset($nombrepruebaparticipantes['4x100'][$i]);
                                }
        
                            }
                        }
                        if($participantemasculino<4){
                            if($participantemasculino!=0){
                                echo'<script type="text/javascript">alert("La inscripcion a la prueba 4x100 masculina no se ha podido realizar por que faltaban participantes");</script>';            
                            }
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
                    $mensaje="Las inscripciones se han creado correctamente";
                    echo '<script>window.location.href = "./indexPrueba.php?mensaje='.$mensaje.'";</script>';
                   }
                   
                }else{
                    echo 'debe rellenar el formulario con los elementos minimos';
            }
        }
        
    }
    
    
}

}