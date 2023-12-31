<?php
ob_start();     // Activa el almacenamiento en el búffer de salida
  
session_start();

 if(!isset($_SESSION['usuario'])|| $_SESSION['tipoUsuario']=='Tutor'){
    include_once "error.html";
}else{
    require_once('../controlador/controladorExcel.php');
    $controlador = new ControladorExcel;
    $pruebas = $controlador->getPruebaExclusiva();
    $pruebas4x100 = $controlador->getPrueba4x100();
    include_once "cabecera.html";
    
?>
        <main>
            <div class="p-4 mb-3 text-dark border border-dark titulo">
                <h5 class="text-center">Exportar inscripciones</h5>
            </div>
            <?php 
            if(isset($_GET['mensaje'])){
                echo '<h5 id="errores">'.$_GET['mensaje'].'</h5>';
            }
            ?>
            <div class="tituloForm">
                <h5>Selecciona las pruebas de las cuales quiere exportar las inscripciones:</h5>
            </div>
            <?php
                if(!empty($pruebas)){

            ?>
            <form action="formExportar.php" method="POST">
                    <div id="contenedor3">
                        <button type="submit" name="guardarpdftodas" class="btn btn-primary btn-submit">Exportar pdf todas las Pruebas</button>
                    </div> 
                <?php
                foreach($pruebas4x100 as $prueba4x100){
                    $num_inscripciones = $prueba4x100['num_inscripciones'];
                    if($num_inscripciones == 0){
                        echo '<div class="box">
                            <span>'.$prueba4x100['nombre'].'&nbsp;&nbsp; (No hay inscripciones)</span>
                            <input type="checkbox" name="checkPruebas[]" value="'.$prueba4x100['idPrueba'].'"disabled>
                        </div>';
                    }else{
                        echo '<div class="box">
                            <span>'.$prueba4x100['nombre'].'</span>
                            <input type="checkbox" name="checkPruebas[]" value="'.$prueba4x100['idPrueba'].'">
                        </div>';
                    }
                }
                foreach($pruebas as $prueba){
                    $num_inscripciones = $prueba['num_inscripciones'];
                    if($num_inscripciones == 0){
                        echo '<div class="box">
                            <span>'.$prueba['nombre'].'&nbsp;&nbsp; (No hay inscripciones)</span>
                            <input type="checkbox" name="checkPruebas[]" value="'.$prueba['idPrueba'].'"disabled>
                        </div>';
                    }else{
                        echo '<div class="box">
                            <span>'.$prueba['nombre'].'</span>
                            <input type="checkbox" name="checkPruebas[]" value="'.$prueba['idPrueba'].'">
                        </div>';
                    }
                }
                ?>
                <div id="formBotones" class="col-auto text-center">
                    <a href="./indexPrueba.php"><button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="./indexPrueba.php" ><button type="submit" name="guardar" class="btn btn-primary btn-submit">Exportar excel</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="submit" name="guardarpdf" class="btn btn-primary btn-submit">Exportar pdf</button>
                                   
                </div>
            </form>
            <?php
                }else{
                    echo '<div class="error">No hay pruebas disponibles.</div>';
                }
            ?>
        </main>
    </body>
    <?php
        include_once "footer.html";
    ?>
</html>
<?php
    if(isset($_POST['guardar'])){
        if(!empty($_POST["checkPruebas"])) {
            $exportar = $controlador->exportarInscripciones($_POST['checkPruebas']);
            // Incluir la librería FPDF
            
        }
    }
    if(isset($_POST['guardarpdf'])){
        if(!empty($_POST["checkPruebas"])) {
            // Incluir la librería FPDF
            $datos=$controlador->exportarInscripcionesPdf($_POST["checkPruebas"]);
           
        }else{
            $mensaje= 'Debes seleccionar al menos una prueba';
            echo '<script>window.location.href = "./formExportar.php?mensaje='.$mensaje.'";</script>';
        }
    }
    if(isset($_POST['guardarpdftodas'])){
      
        $datos=$controlador->generarPDFTodas();
    }
}    
    

?>