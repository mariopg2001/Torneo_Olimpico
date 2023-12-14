<?php
    ob_start();     // Activa el almacenamiento en el búffer de salida
    session_start();
    if(!isset($_SESSION['usuario']) || $_SESSION['tipoUsuario']=='Tutor'){
        include_once "error.html";
    }else{
        require_once('../controlador/controladorFechas.php');
        $controlador = new ControladorFechas;
        $fechas = $controlador->getFechas();
        // Incluir la cabecera de la página
        include_once "cabecera.html";
?>
    <body>
        <main>
            <div class="p-4 mb-3 text-dark border border-dark titulo">
                <h5 class="text-center">Modificar fechas inscripciones</h5>
            </div>
            <?php
                if(isset($_GET['mensaje'])){
                    echo '<span id="mensajeError">'.$_GET['mensaje'].'</span><br><br>';
                }
            ?>
            <div id="centrar">
                <!-- Formulario para modificar la fecha del periodo de inscripciones -->
                <form action="formFechasInscripciones.php" method="POST">
                    <?php
                    // Definir la variable con el valor de la fecha actual
                    $fechaActual = date('Y-m-d');
                    foreach($fechas as $fecha){
                    echo '<div class="form-row m-4">
                                <label for="fechaInicio"><h5>Fecha Inicio de inscripción</h5></label>
                                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="'.$fecha['fechaInicio'].'" min="'.$fechaActual.'">
                            </div><br>
                            <div class="form-row m-4">
                                <label for="fechaFin"><h5>Fecha Fin de inscripción</h5></label>
                                <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="'.$fecha['fechaFin'].'" min="'.$fechaActual.'">
                            </div>';
                    }
                    ?>
                    <div class="p-5 col-auto text-center">
                        <a href="../index.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button></a>
                        <button type="submit" name="guardar" class="btn btn-primary btn-submit">Guardar</button>
                    </div>
                </form>
            </div>
        </main>
    </body>
    <?php
        // Incluir el footer de la página
        include_once "footer.html";
    ?>
</html>
<?php
    if(isset($_POST['guardar'])){
        if(!empty($_POST["fechaInicio"]) AND !empty($_POST['fechaFin'])){
            $resultado = $controlador->modificarFechas($_POST['fechaInicio'], $_POST['fechaFin']);
            echo '<script>window.location.href = "../index.php";</script>';    // Redirigir al usuario a la página principal después de exportar los datos
            exit;
        }
    }
    }
?>