<?php
session_start();
  
if(!isset($_SESSION['usuario'])|| $_SESSION['tipoUsuario']=='Tutor'){
    include_once "error.html";
}else{
    include_once "cabecera.html";
?>
        <main>
            <div class="p-4 mb-3 text-dark border border-dark titulo">
                <h5 class="text-center ">Modificar Pruebas</h5>
            </div>
            <?php
                if(isset($_GET['mensaje'])){
                    echo '<h5>'.$_GET['mensaje'].'</h5>';
                }
                require_once('../controlador/controladorPrueba.php');
                if(empty($_POST)){
                    $controlador= new ControladorPrueba;
                    $responsable=$controlador->responsable();
                    $result=$controlador->pruebaDatos();
                    if(isset($_GET['id'])){
                        foreach($result as $fila  ){
                            if($fila['idPrueba']==$_GET['id']){
                                $prueba=$fila['nombre'];
                                $responsable2=$fila['idResponsable'];
                                $participantes=$fila['Max_Participantes'];
                            }
                        }
                    }
                        echo '<form method="POST" action="form_update.php">
                            <input class="inp" type="text" name="id" hidden value="'.$_GET['id'].'"/>
                            <br><div class="form-row m-4">
                                <label for="name"><h5>* Nombre de la prueba</h5></label>';
                            if($prueba== '4x100'){
                              echo'  <input readonly class="form-control" type="text" name="nombre" value="'.$prueba.'"/>';
                            }else{
                              echo'  <input class="form-control" type="text" name="nombre" value="'.$prueba.'"/>';

                            }
                           echo' </div><br>
                            <div class="form-row m-4">
                                <label for="Responsable"><h5>Responsalble de la prueba</h5></label>
                                <select name="responsable" id="">';

                            foreach($responsable as $fila2){
                                if( $responsable2==$fila2['idUsuario']){
                                    echo '<option value='.$fila2['idUsuario'].' selected>'.$fila2['nombre'].'</option>';
                                }else{
                                    echo '<option value='.$fila2['idUsuario'].'>'.$fila2['nombre'].'</option>';
                                }
                            }
                            echo '</select></div><br>
                            
                            <div class="form-row m-4">
                                <label for="name"><h5>* Maximo de participantes de una prueba (por clase)</h5></label>';
                                if($prueba== '4x100'){
                               echo' <input readonly type="number" name="participantes" class="form-control" value="'.$participantes.'" id="name" min="0">';
                                }else{
                               echo' <input type="number" name="participantes" class="form-control" value="'.$participantes.'" id="name" min="0">';

                                }
                            echo'</div><br>
                            <div class="p-5 col-auto text-center">
                                <a href="./indexPrueba.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></a>
                                <input type="submit" class="btn btn-primary btn-submit" id="anadir" value="Aceptar"/>
                            </div>
                        </form>';
                }else{
                    if(!empty($_POST['nombre']) && !empty($_POST['participantes'])){
                        $controlador= new ControladorPrueba;
                        $controlador->modificar($_POST);
                    }else{
                        $mensaje= 'Debes rellenar los campos obligatorios(*)';
                        echo '<script>window.location.href = "./form_update.php?mensaje='.$mensaje.'";</script>';
                    }
                }
            ?>
        </main>
        <?php
            include_once "footer.html";
}
        ?>
    </body>
</html>