<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        require_once( './vistas/login.php');
    }else{

    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Vistas/estilos/style.css">
    <title>Inicio</title>
</head>
<body>
    <a href="./Vistas/Flogin.php"><button>Cerrar Sesion</button></a>
    <div id="contenedor">
        <h1>Has iniciado sesion correctamente</h1>
        <?php
        // require_once( './controlador/controladorIndex.php');
        // $controlador= new ControladorIndex();
        // $nprofesor= $controlador->nombreus($_SESSION['usuario']);
            echo ' Bienvenido '.$_SESSION['usuario'];
        ?> 
    </div>
    
</body>
</html>
<?php
    }
?>