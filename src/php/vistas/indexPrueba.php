<?php   
    session_start();
  

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
        $usuario=$controlador->tipoUsuario($_SESSION['usuario']);
        $nombreUsuario=$controlador->nombreUsuario($_SESSION['usuario']);
        $nfilas=$modelo->filas;
        echo $nfilas;

    include_once "cabecera.html";
?>
        <main>
            <div class="p-4 mb-3 text-dark border border-dark titulo">
                <h5 class="text-center">Listado de Pruebas</h5>
            </div>
            <?php 
             
             
             if($usuario=='Tutor'){
                 echo' 
                 <div class="top-right-button-container">
                 <a href="./formularioInscripcion.php"> <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">INSCRIPCIONES</button></a>
              </div>';
             }
             if($usuario=='Administrador'){
                 echo'
                 <div class="top-right-button-container">
                 <a href="./formularioPrueba.php"> <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">NUEVA PRUEBA</button></a>
              </div>';
             }
         ?>
            <!-- <div class="top-right-button-container">
               <a href="./formularioPrueba.php"> <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">NUEVA PRUEBA</button></a>
            </div> -->
            <br><br><br>
            <div id="middle">
                <?php
                 if($pruebas[1]>0){
                    echo ' <table>
                    <tr>
                    <th>Nombre</th>
                    <th>Responsable</th>
                    <th>Participantes</th>';
                    if($usuario=='Administrador'){
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
                               
                                if($usuario=='Administrador'){
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
            </div>
        </main>
        <?php
            include_once "footer.html";
            }
        ?>