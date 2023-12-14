<?php
    
    require_once "../config/config.php";

    //use PhpOffice\PhpSpreadsheet\{Spreadsheetl,IOFactory};
    // use PhpOffice\PhpSpreadsheet\Spreadsheet;
    // use PhpOffice\PhpSpreadsheet\IOFactory;
    
    class ModeloExcel{
        public $filas;
        private $conexion;
        public function __construct(){
            $this->conexion=$this->conectar();
        }
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
        // Función para obtener las pruebas exclusivas existentes en la base de datos para ser mostradas en la vista
        public function getPruebaExclusiva(){
            $sql = 'SELECT idPrueba, nombre, COUNT(idPruebaExclusiva) AS num_inscripciones, tipo FROM TO_Pruebas 
                        LEFT JOIN TO_Inscripciones_Exclusivas ON idPrueba = idPruebaExclusiva
                        WHERE tipo = "E"
                        GROUP BY idPrueba';
            $result = $this->conexion->query($sql);

            $sql1 = 'SELECT * from TO_Pruebas';
            $result1 = $this->conexion->query($sql1);

            return $result;
        }
        // Función para obtener las pruebas 4x100 existentes en la base de datos para ser mostradas en la vista
        public function getPrueba4x100(){
            $sql = 'SELECT idPrueba, nombre, tipo, (SELECT COUNT(*) FROM TO_Inscripciones4x100) AS num_inscripciones
                        FROM TO_Pruebas
                        WHERE tipo = "4";';
            $result = $this->conexion->query($sql);

            return $result;
        }
        // Función para exportar los datos de las inscripciones a pruebas 
        public function exportarInscripciones($pruebas){
            try{
                foreach($pruebas as $prueba){
                    $sql = "SELECT * FROM TO_Pruebas WHERE idPrueba = ".$prueba."";
                    $resultado = $this->conexion->query($sql);
                    if($resultado){
                        $fila = $resultado->fetch_assoc();
                        if($fila['tipo'] == '4'){
                            $array4x100 = $fila['idPrueba'];
                        }else{
                            $arrayExclusiva[] = $fila['idPrueba'];
                        }
                    }
                }

                $arrayPruebas = array($array4x100, $arrayExclusiva);

                return $arrayPruebas;
            }catch(Exception $e){
                echo 'Error: ',  $e->getMessage(), "\n";
            }
        }

        // Función para obtener los datos de las inscripciones 4x100
        public function getDatos4x100(){
            try{
                $sql = "SELECT a.nombre as 'nombreAlumno', s.nombre as 'nombreSeccion', a.sexo, ca.nombre as 'nombreCategoria' FROM Alumnos a 
                            INNER JOIN Secciones s ON a.idSeccion = s.idSeccion 
                            INNER JOIN Cursos c ON c.idCurso = s.idCurso 
                            INNER JOIN Etapas e ON e.idEtapa = c.idEtapa 
                            INNER JOIN TO_CategoriasEtapas ce ON ce.idEtapa = e.idEtapa
                            INNER JOIN TO_Categorias ca ON ce.idCategoria = ca.idCategoria
                            INNER JOIN TO_Inscripciones4x100 ic ON ic.participante1 = a.idAlumno OR ic.participante2 = a.idAlumno OR ic.participante3 = a.idAlumno OR ic.participante4 = a.idAlumno
                                ORDER BY ca.idCategoria, a.sexo, s.idSeccion, a.nombre";

                //echo "Consulta SQL: ".$sql;
                $resultado = $this->conexion->query($sql);
                return $resultado;
            }catch(Exception $e){
                echo 'Error: ',  $e->getMessage(), "\n";
            }
        }

        public function getDatosPrueba($prueba){
            try{
                $sql = "SELECT * FROM TO_Pruebas WHERE idPrueba = ".$prueba."";
                $result = $this->conexion->query($sql);
                return $result;
            }catch(Exception $e){
                echo 'Error: ',  $e->getMessage(), "\n";
            }
        }

        // Función para obtener los datos de las inscripciones Exclusivas
        public function getDatosExclusivas($idPrueba){
            try{
                $sql = "SELECT a.nombre as 'nombreAlumno', s.nombre as 'nombreSeccion', a.sexo, ca.nombre as 'nombreCategoria' FROM Alumnos a 
                            INNER JOIN Secciones s ON a.idSeccion = s.idSeccion 
                            INNER JOIN Cursos c ON c.idCurso = s.idCurso 
                            INNER JOIN Etapas e ON e.idEtapa = c.idEtapa 
                            INNER JOIN TO_CategoriasEtapas ce ON ce.idEtapa = e.idEtapa
                            INNER JOIN TO_Categorias ca ON ce.idCategoria = ca.idCategoria
                            INNER JOIN TO_Inscripciones_Exclusivas ie ON ie.idAlumno = a.idAlumno
                                WHERE ie.idPruebaExclusiva = $idPrueba
                                ORDER BY ca.idCategoria, a.sexo, s.idSeccion, a.nombre";

                //echo "Consulta SQL: ".$sql;
                $resultado = $this->conexion->query($sql);
                return $resultado;
            }catch(Exception $e){
                echo 'Error: ',  $e->getMessage(), "\n";
            }
        }
    }