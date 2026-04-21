<?php
    if($_SERVER["REQUEST_METHOD"]!="POST" && !isset($_POST["buscar"])){
        header("Location:../index.html");
        exit();
    }
    include_once("../php/conexionBBDD.php");
    session_start();
    $noMedico = false;
    $fecha = $_POST["dia"];
    $hora = $_POST["hora"];
    try{
        $conexion = new PDO ($url, $user, $pass);
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
        sleep(3);
        header("Location:../index.html");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Cita - Plenicare</title>
    <link rel="stylesheet" href="../css/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../css/styleHeaderFooter.css">
    <link rel="stylesheet" href="../css/styleSobreNosotros.css">
    <link rel="icon" href="../media/simbolo.png">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php 
        if($_SESSION("tipo")=="paciente"){
            include_once("../components/headerPaciente.php");
        }else if($_SESSION("tipo")=="medico"){
            include_once("../components/headerMedico.php");
        }
    ?>
    <!----------------------------------------------------------------------------------------------- FORMULARIO -->
    <main>
        <form class="row g-3 needs-validation m-5" method="POST" action="../php/registrarCita.php" novalidate>
            <div class="col-md-5 position-relative">
                <label for="fecha" class="form-label">Día</label>
                <input type="text" class="form-control" name="fecha" value="<?php echo $fecha ?>" readonly>
            </div>
            <div class="col-md-5 position-relative">
                <label for="hora" class="form-label">Hora</label>
                <input type="text" class="form-control" name="hora" value="<?php echo $hora ?>" readonly>
            </div>
            <!---------------------------------------------------------------------------- PACIENTE BUSCA MEDICO -->
            <div class="col-md-4 position-relative">
                <label for="medico" class="form-label">Médica / Médico</label>
                <select class="form-select" name="medico" required>
                <option selected disabled value="">---</option>
                <?php
                    try{
                        $sentencia = "SELECT DISTINCT m.id_medico as id, CONCAT(m.nombre, ' ', m.apellidos) as nombre_completo 
                                        from medico m 
                                        WHERE m.especialidad = 'Medicina General' 
                                            AND NOT EXISTS (
                                                SELECT 1
                                                FROM pedir_cita pc
                                                WHERE pc.id_medico = m.id_medico
                                                    AND pc.fecha = :fecha
                                                    AND pc.hora = :hora
                                            )";
                        $sql = $conexion->prepare($sentencia);
                        $sql -> bindParam(":fecha",$fecha);
                        $sql -> bindParam(":hora",$hora);
                        $sql -> setFetchMode(PDO::FETCH_ASSOC);
                        if($sql->execute()){
                            if ($sql->rowCount()>0){
                                while($tupla = $sql->fetch()){
                                    echo "<option value=\"".$tupla["id"]."\">".$tupla["nombre_completo"]."</option>";
                                }
                            } else {
                                $noMedico = true;
                            }
                        }
                    } catch (PDOException $e) {
                        echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
                        sleep(3);
                        header("Location:../index.html");
                        exit();
                    }
                ?>
                </select>
                <div>
                    <?php 
                        if($noMedico) echo "<small style=\"color:red\">No hay médicos disponibles para la fecha y/u hora especificados.</small>";
                    ?>
                </div>
                <div class="invalid-tooltip">
                    Escoja un médico disponible.
                </div>
            </div>
            <!---------------------------------------------------------------------------- MEDICO BUSCA PACIENTE -->
            <!-- FALTA POR HACER -->
            
            <div class="col-md-4 position-relative">
                <label for="tipo" class="form-label">Tipo de cita</label>
                <select class="form-select" name="tipo" required>
                    <option selected disabled value="">---</option>
                    <option value="TELEFONICA">Telefónica</option>
                    <option value="PRESENCIAL">Presencial</option>
                    <option value="URGENCIA">Urgencia</option>
                </select>
            </div>
            <div class="col-4 position-relative">
                <button class="btn btn-primary mt-4" name="formCita" type="submit">Solicitar cita</button>
            </div>
        </form>
    </main>
    <!----------------------------------------------------------------------------------------  FOOTER    -->
        <?php @include_once("../components/footer.php"); ?>
    <!---------------------------------------------------------------------------------------- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>