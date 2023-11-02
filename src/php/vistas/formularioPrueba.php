<?php
require_once('../controlador/controladoPrueba.php');
$controlador= new ControladorPrueba;
$responsable=$controlador->responsable();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="formularioPrueba.php" method="post">
        <div class="form-row m-4">
            <label for="name"><h5>Nombre de la prueba</h5></label>
            <input type="text" class="form-control"  name="nombrePrueba" id="name" >
        </div>
        <div class="form-row m-4">
            <label for="Responsable"><h5>Responsalble de la prueba</h5></label>
            <select name="responsable" id="">
                <?php

                    while($fila=$responsable->fetch_assoc())
                    {
                        echo '<option value='.$fila['idUsuario'].'>'.$fila['nombre'].'</option>';
                    }
                ?>
            </select>
        </div>
        <div class="form-row m-4">
            <label for="name"><h5>Maximo de participantes de una prueba (por clase)</h5></label>
            <input type="number" name="participantes" class="form-control" placeholder="ej.4" name="name" id="name" min="0">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" name="guardar" class="btn btn-primary btn-submit">Guardar</button>
        </div>
  
    </form>
</body>
</html>
<?php
    if(isset($_POST['guardar'])){
       if(!empty($_POST['participantes'])){
            if(!empty($_POST['nombrePrueba'])){
                        $Prueba= $controlador->InsertarPrueba($_POST['participantes'],$_POST['finicio'],$_POST['Ffin'],$_POST['responsable'], $_POST['nombrePrueba']);
                        header("Location: ../index.php"); // Redirige al usuario a la página de confirmación después de realizar la inserción
                        exit;
            }else{
                echo 'Debes introducir un nombre a la prueba';
            }
       }else{
            echo 'Debes poner el maximo de participantes';
       }
    }
?>