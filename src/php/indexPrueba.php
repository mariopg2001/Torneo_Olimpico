<?php
 if(isset($_GET['nombre']) && isset($_GET['id'])){
    echo '
        <h2>AVISO</h2>
        <h4>¿Seguro que quieres eliminar la categoria: '.$_GET['nombre'].' ?</h4>
        <button><a href="./vistas/borrar.php?id='.$_GET['id'].'">Sí</button>
        <button><a href="index.php">No</button>
    ';
}
else{
    require_once('./controlador/controladoIndex.php');
    $controlador= new ControladorIndex;

    $pruebas=$controlador->pruebas();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/style.css">
        <title>Alta Pruebas </title>
    </head>
    <body>
        <header>
            <img src="src/logo.png" class="img-responsive" alt="logo"/>
            <span>Menu</span>
            <span>Cerrar sesión</span>
        </header>
        <main>
            <div class="p-4 mb-3 bg-warning text-dark border border-dark">
                <h5 class="text-center ">Alta de Pruebas</h5>
            </div>
            <div class="top-right-button-container">
               <a href="./vistas/formularioPrueba.php"> <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">NUEVA PRUEBA</button></a>
            </div>
            <br><br><br>
            <div class="contenedor">
            <table>
            <tr>
                <th>Nombre</th>
                <th>Responsable</th>
                <th>Participantes</th>
                <th>Editar</th>
                <th>Borrar</th>
                <th>Inscripcion</th>
            </tr>
            
           <?php
           if($pruebas->num_rows>0){
               while($fila=$pruebas->fetch_assoc())
               {
                    $responsable=$controlador->responsable($fila['idResponsable']);
                    echo '
                        <tr>
                            <td>'.$fila['nombre'].'</td>
                            <td>'.$responsable.'</td>
                            <td>'.$fila['Max_Participantes'].'</td>
                            <td><a href="./vistas/form_update.php?id='.$fila["idPrueba"].'"><img src="../imagen/lapiz.png"></a></td>
                            <td><a href="index.php?nombre='.$fila['nombre'].'&id='.$fila['idPrueba'].'"><img src="../imagen/basura.jpg"></a></td>
                            <td><a href="./vistas/form_inscripcion.php?id='.$fila["idPrueba"].'"><img src="../imagen/inscripcion.jpg"></a></td>
                        </tr>';
               }
            }else{
                echo '<h4>No hay pruebas añadidas</h4>';
            }
           ?>
           </table>
           </div>
        </main>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>
<?php }?>