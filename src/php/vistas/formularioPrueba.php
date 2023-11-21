<?php
    require_once('../controlador/controladorPrueba.php');
    $controlador = new ControladorPrueba;
    $responsable = $controlador->responsable();

    include_once "cabecera.html";
?>
        <main>
            <div class="p-4 mb-3 text-dark border border-dark titulo">
                <h5 class="text-center ">Alta de Pruebas</h5>
            </div>
            <form action="formularioPrueba.php" method="post">
                <div class="form-row m-4">
                    <label for="name"><h5>* Nombre de la prueba</h5></label>
                    <input type="text" class="form-control"  name="nombrePrueba" id="name" >
                </div><br>
                <div class="form-row m-4">
                    <label for="Responsable"><h5>Responsalble de la prueba</h5></label>
                    <select name="responsable" id="" class="form-control">
                        <?php
                            foreach($responsable as $fila){
                                echo '<option value='.$fila['idUsuario'].'>'.$fila['nombre'].'</option>';
                            }
                        ?>
                    </select>
                </div><br>
                <div class="form-row m-4">
                    <label for="name"><h5>* Maximo de participantes de una prueba (por clase)</h5></label>
                    <input type="number" name="participantes" class="form-control" placeholder="ej.4" name="name" id="name" min="0">
                </div><br>
                <div class="p-5 col-auto text-center">
                    <a href="./indexPrueba.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></a>
                    <button type="submit" name="guardar" class="btn btn-primary btn-submit">Guardar</button>
                </div>
            </form>
        </main>
    </body>
    <?php
        include_once "footer.html";
    ?>
</html>
<?php
    if(isset($_POST['guardar'])){
        if(!empty($_POST['nombrePrueba']) && !empty($_POST['participantes'])){
            $Prueba= $controlador->InsertarPrueba($_POST['participantes'],$_POST['responsable'], $_POST['nombrePrueba']);
            echo '<script>window.location.href = "./indexPrueba.php";</script>';    // Redirige al usuario a la página de confirmación después de realizar la inserción
            exit;
        }else{  
            echo 'Debes rellenar los campos obligatorios (*)';
        }
    }
?>