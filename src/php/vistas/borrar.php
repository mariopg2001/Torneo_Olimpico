<?php
    $idPrueba=$_GET['id'];
    require_once('../controlador/controladorPrueba.php');
    $controlador= new ControladorPrueba;
    $resultado=$controlador->eliminar($idPrueba);   
?>