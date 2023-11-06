<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        require_once('../controlador/controladoPrueba.php');
        if(empty($_POST)){
            $controlador= new ControladorPrueba;
            $responsable=$controlador->responsable();
            $result=$controlador->Prueba();
            if(isset($_GET['id'])){
                while($fila = $result ->fetch_assoc()){
                    if($fila['idPrueba']==$_GET['id']){
                        $prueba=$fila['nombre'];
                        $responsable2=$fila['idResponsable'];
                        $participantes=$fila['Max_Participantes'];
                    }
                }
            }
                echo '<form method="POST" action="form_update.php">
                    <input class="inp" type="text" name="id" hidden value="'.$_GET['id'].'"/>
                    <label for="name"><h5>* Nombre de la prueba</h5></label>
					<input class="inp" type="text" name="nombre" value="'.$prueba.'"/><br><br>
                    <label for="Responsable"><h5>Responsalble de la prueba</h5></label>
                    <select name="responsable" id="">';

                    while($fila2=$responsable->fetch_assoc())
                    {
                        if( $responsable2==$fila2['idUsuario']){
                            echo '<option value='.$fila2['idUsuario'].' selected>'.$fila2['nombre'].'</option>';
                        }else{
                            echo '<option value='.$fila2['idUsuario'].'>'.$fila2['nombre'].'</option>';
                        }
                       
                    }
					echo '</select>
                    
                    <div class="form-row m-4"></br></br>
                        <label for="name"><h5>* Maximo de participantes de una prueba (por clase)</h5></label>
                        <input type="number" name="participantes" class="form-control" value="'.$participantes.'" id="name" min="0">
                        </div>
                        <input type="submit" id="anadir" value="Aceptar"/>
					</form>';
        }else{
            if(!empty($_POST['nombre']) && !empty($_POST['participantes'])){
                $controlador= new ControladorPrueba;
                $controlador->modificar($_POST);
            }else{
                echo 'Debes rellenar los campos obligatorios (*)';
            }
        }
    ?>

</body>
</html>