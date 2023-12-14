<?php
    require_once "../config/config.php";

    //use PhpOffice\PhpSpreadsheet\{Spreadsheetl,IOFactory};
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    
    class ModeloFechas{
        public $filas;
        private $conexion;
        public function __construct(){
            $this->conexion=$this->conectar();
        }
        // Función que realiza la conexión con la base de datos
        public function conectar(){
            $conexion = new mysqli(SERVER,USU,CONTRA,BBDD);
            $conexion->set_charset('utf8');
            if($conexion->connect_errno){
                echo 'la conexion falló'. $conexion->connect_error;
                die();
            }else{
                return $conexion;
            }
        }
        // Función para obtener la fecha inicio y la fecha de fin del periodo de inscripciones
        public function getFechas(){
            $sql = 'SELECT * FROM TO_FechaInscripcion';
            $result = $this->conexion->query($sql);

            return $result;
        }
        // Función para modificar la fecha de inscripción en la base de datos
        public function modificarFechas($fechaInicio, $fechaFin){
            try{
                if($fechaInicio >= $fechaFin){
                    $mensaje = 'LA FECHA DE INICIO DEBE SER ANTERIOR A LA DE FIN';
                    // return $mensaje;
                    echo '<script>window.location.href = "./formFechasInscripciones.php?mensaje='.$mensaje.'";</script>';
            }else{
                    $sql = "UPDATE TO_FechaInscripcion SET fechaInicio = '$fechaInicio', fechaFin = '$fechaFin'";
                    $result = $this->conexion->query($sql);
                    return 0;
                }
            }catch(Exception $e){
               echo $e->getCode();
               echo $e->getMessage();
            }
        }
    }