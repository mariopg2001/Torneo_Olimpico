<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
        <script src="https://kit.fontawesome.com/5b31d65f7c.js" crossorigin="anonymous"></script>
        <title>Torneo Olimpico</title>
    </head>
    <body>
       <div id="contenedor">
            <div id="form">
                <h2>INICIAR SESIÓN</h2>
                <form action="./vistas/login.php" method="post">
                    <label>CORREO:</label><br>
                    <input type="text" class="inputlog" name="correo" placeholder="Correo"><br><br>
                    <?php
                        if(isset($_GET['mensaje'])){
                            echo ' <span id="mensajeError">'. $_GET['mensaje'].'</span><br><br>';
                        }?>
                    <div class="centrarDiv"><input type="submit" id="buttonlog" value="Acceder" name="login"></div>
                </form>
            </div>
        </div>
    </body>
    <?php
            include_once "footer.html";
            
        ?>
</html>
<?php
if(isset($_POST['login'])){
        if(!empty($_POST['correo'])){
            require_once '../controlador/controladorlog.php';
            $controlador= new Controladorlog();
            $correo=$_POST['correo'];
            $result=$controlador->iniciarSesion($correo);
          
        }else{
            $mensaje ='Los campos han sido enviados sin informacion';
            echo '<script>window.location.href = "../index.php?mensaje='.$mensaje.'";</script>';
        }
    }
?>