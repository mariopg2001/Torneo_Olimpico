<?php   
    session_start();
  
    if(!isset($_SESSION['usuario'])){
        include_once "error.html";
    }else{
    if(isset($_GET['nombre']) && isset($_GET['id'])){
        include_once "cabecera.html";
        echo '
            <div class="p-4 mb-3 text-dark border border-dark titulo">
                <h3 class="text-center ">AVISO</h3>
            </div>
            <div class="p-5 col-auto text-center">
                <h4>¿Seguro que quieres eliminar la Prueba: '.$_GET['nombre'].' ?</h4>
                <a href="./borrar.php?id='.$_GET['id'].'"><button type="button" class="btn btn-primary btn-submit">Sí</button></a>
                <a href="indexPrueba.php"><button type="button" class="btn btn-secondary">No</button></a>
            </div>
        ';
    }
    else{
        require_once('../controlador/controladorPrueba.php');
        $controlador= new ControladorPrueba;
        require_once('../modelo/modeloPrueba.php');
        $modelo=new ModeloPrueba();

        
        $pruebas=$controlador->pruebasyfilas();
        $nfilas=$modelo->filas;
        echo $nfilas;
       
    include_once "cabecera.html";
?>
        <main>
            <div class="p-4 mb-3 text-dark border border-dark titulo">
                <h5 class="text-center">Listado de pruebas</h5>
            </div>
          
            <?php 
             
             
             if($_SESSION['tipoUsuario']=='Tutor'){
                 echo' <div id="contenedor2">
                 <div class="top-right-button-container">
                 <a href="./formularioInscripcion.php"> <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">INSCRIPCIONES</button></a>
                </div>
                <div>
                    <h5>CADA SECCIÓN REPRESENTARÁ A SU CLASE</h5>
                    <h6>*  Cada participante sólo podrá participar en una prueba excepto en 4 x 100 relevos que pueden repetir pero han de ser diferentes los corredores.</h6>
                    <h6>*  El número máximo de participantes por prueba se indica al lado de cada una</h6>
                </div></div>';
             }
             if($_SESSION['tipoUsuario']=='Coordinador de Actividades'){
                 echo'
                 <div class="top-right-button-container" id="contenedor2">
                 <a href="./formularioPrueba.php"> <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">Nueva Prueba</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 <a href="./formExportar.php" > <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">Descargar Inscripciones</button></a>
                 </div>';
             }
             if(isset($_GET['mensaje'])){
                echo '<h5 id="errores">'.$_GET['mensaje'].'</h5>';
             }
             if(isset($_GET['mensaje2'])){
                echo '<h5 id="errores2">'.$_GET['mensaje2'].'</h5>';
             }
         ?>
           
            <br><br><br>
            <div id="middle">
                <?php
                 if($pruebas[1]>0){
                    echo ' <table>
                    <tr>
                    <th>Nombre</th>
                    <th>Responsable</th>
                    <th>Maximo de participantes por clase</th>';
                    if($_SESSION['tipoUsuario']=='Coordinador de Actividades'){
                        echo'
                        <th>Editar</th>
                        <th>Borrar</th>
                       
                        </tr>';
                    }
                    foreach($pruebas[0] as $fila){
                        
                            $responsable=$controlador->responsablePrueba($fila['idResponsable']);
                            echo '
                                <tr>
                                    <td>'.$fila['nombre'].'</td>
                                    <td>'.$responsable.'</td>
                                    <td>'.$fila['Max_Participantes'].'</td>';
                               
                                if($_SESSION['tipoUsuario']=='Coordinador de Actividades'){
                                    echo'<td><a disabled href="./form_update.php?id='.$fila["idPrueba"].'"><i class="fa-solid fa-pen"></i></a></td>';

                                    if($fila['nombre']!= '4x100'){
                                        echo '<td><a href="indexPrueba.php?nombre='.$fila['nombre'].'&id='.$fila['idPrueba'].'"><i class="fa-solid fa-trash"></i></a></td>';

                                    }else{
                                        echo '<td></td>';

                                    }

                                }
                               
                            echo'</tr>';
                    }
                    }else{
                        echo '<h4>No hay pruebas añadidas</h4>';
                    }
                ?>
                </table>
                <div id="centrar2">
                    <a href="../index.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button></a>
                </div>
            </div>
        </main>
        <?php
            include_once "footer.html";
            }
        }
        ?>