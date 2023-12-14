<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        require_once( './vistas/login.php');
    }else{

        include_once "./vistas/cabeceraIndex.html";
?>
<!DOCTYPE html>
<html>
<main>
        <div class="p-4  text-dark border border-dark titulo">
            <h5 class="text-center">Página principal</h5>
        </div>

        <div id="centrar">
            <span class="botones">
                <a href="./vistas/indexPrueba.php"><button type="button" class="btn btn-primary btn-lg mr-1">Listar Pruebas</button></a>
            </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            if($_SESSION['tipoUsuario']!='Tutor'){
            echo '<span class="botones">
                <a href="./vistas/formExportar.php"><button type="button" class="btn btn-primary btn-lg mr-1">Descargar Inscripciones</button></a>
            </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="botones">
                <a href="./vistas/formFechasInscripciones.php"><button type="button" class="btn btn-primary btn-lg mr-1">Modificar Fecha Inscripciones</button></a>
            </span>';
            }
            ?>
        </div>
    </main>
    <?php
        include_once "./vistas/footer.html";
    }
    ?>