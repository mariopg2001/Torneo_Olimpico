<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Alta Pruebas </title>
</head>
<body>
    <h5>Alta de Pruebas</h5>
<div class="top-right-button-container">
    <button type="button" data-toggle="modal" data-target="#FormularioAlta" class="btn btn-primary btn-lg top-right-button mr-1">NUEVA PRUEBA</button>
</div>
<div class="modal fade" id="FormularioAlta" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mockup Nueva Prueba</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form>
                   
                <div class="form-row m-4">
                      <label for="name">Nombre de la prueba</label>
                      <input type="text" class="form-control"  name="name" id="name" >
                </div>

                <div class="form-row m-4">
                    <label for="description">Responsalble de la prueba</label>
                    <select name="Profesores" id="">
                        <?php
                            for($i=1;$i<=5;$i++){
                                echo "<option value='Profesor".$i."'>Profesor".$i."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-row m-4">
                      <label for="name">Fecha Inicio de inscripcion</label>
                      <input type="date"  >
                </div>
                <div class="form-row m-4">
                      <label for="name">Fecha Fin de inscripcion</label>
                      <input type="date"  >
                </div>
                <div class="form-row m-4">
                      <label for="name">Maximo de participantes de una prueba (por clase)</label>
                      <input type="number" class="form-control" placeholder="ej.4"  name="name" id="name" >

                </div>
                <div class="form-row m-4">
                    <label for="name">Tipo de prueba</label>
                    <select name="Tipos" id="">
                        <option value="0">Exclusiva</option>
                        <option value="1">General</option>
                        
                    </select>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>