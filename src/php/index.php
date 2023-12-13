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
                <a href="./vistas/indexPrueba.php"><button type="button" class="btn btn-primary btn-lg mr-1">LISTAR PRUEBAS</button></a>
            </span>
            <?php
            if($_SESSION['tipoUsuario']!='Tutor'){
            echo '<span class="botones">
                <a href="./vistas/formExportar.php"><button type="button" class="btn btn-primary btn-lg mr-1">EXPORTAR LISTADOS</button></a>
            </span>';
            }
            ?>
        </div>
    </main>
    <?php
        include_once "./vistas/footer.html";
    }
    ?>