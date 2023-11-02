<?php
$idPrueba=$_GET['id'];
require_once('../controlador/controladoPrueba.php');
$controlador= new ControladorPrueba;
$resultado=$controlador->eliminar($idPrueba);

?>