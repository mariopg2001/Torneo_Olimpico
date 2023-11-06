

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Inicio</title>
</head>
<body>
    
    <div id="contenedor">
        <form action="./vistas/login.php" method="post">
            <label>Correp</label><br>
            <input type="text" name="correo"><br>
            <label>Usuario</label><br>
            <input type="password" id="usuario" name="usuario">
            <br><br>
            <?php
                if(isset($_GET['mensaje'])){
                    echo ' <span id="mensajeError">'. $_GET['mensaje'].'</span><br><br>';
                }?>
            
            <input type="submit" value="Acceder" name="login">
        </form>
    </div>
</body>
</html>
<?php
        if(!empty($_POST['correo']) && !empty($_POST['usuario'])){
            require_once '../controlador/controladorlog.php';
            $controlador= new Controladorlog();
            $correo=$_POST['correo'];
            $usuario=$_POST['usuario'];
            $controlador->iniciarSesion($correo, $usuario);
        }
?>