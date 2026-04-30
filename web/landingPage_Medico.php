<?php
    session_start();
    if(!isset($_SESSION["login"]) || $_SESSION["tipo"] != "medico"){
        header("Location:../index.php");
        exit();
    }
    include_once("../php/conexionBBDD.php");

    $mensaje = "";
    if(isset($_GET["ok"]) && $_GET["ok"] == "historial_creado"){
        $mensaje = "Historial médico registrado correctamente";
    }

    try {
        $conexion = new PDO ($url, $user, $pass);
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
        sleep(3);
        header("Location:../index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Área - PleniCare</title>
    <link rel="stylesheet" href="../css/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styleLPMedico.css">
    <link rel="stylesheet" href="../css/styleHeaderFooter.css">
    <link rel="icon" href="../media/simbolo.png">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php
        include_once("../components/headerMedico.php");
    ?>
    <!----------------------------------------------------------------------------------------------------- MAIN -->
    <main class="container-fluid my-4">
        <?php if(!empty($mensaje)): ?>
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <?= $mensaje ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <div class="row">
            <!----------------------------------------------------------------------------------- PANEL DE CITAS -->
            <section class="col-lg-3 col-md-4 mb-4">
                <div class="card panel-citas shadow h-100">
                    <div class="card-header text-center">
                        <h4 class="mb-0">Citas de Hoy</h4>
                    </div>
                    <div class="card-body citas-scroll">
                        <?php
                            $sentencia = "SELECT pc.hora, pc.tipo, CONCAT(p.nombre, ' ', p.apellidos) as paciente
                                        FROM pedir_cita pc
                                        JOIN paciente p ON pc.id_paciente = p.id_paciente
                                        WHERE pc.id_medico = :medico
                                        AND pc.fecha = CURDATE()
                                        ORDER BY pc.hora ASC";
                            $sql = $conexion->prepare($sentencia);
                            $sql->bindParam(":medico", $_SESSION["user"]);
                            $sql->setFetchMode(PDO::FETCH_ASSOC);
                            if($sql->execute()){
                                if($sql->rowCount() > 0){
                                    while($cita = $sql->fetch()){
                                        echo "
                                        <div class='card cita-card mb-3'>
                                            <div class='card-body'>
                                                <h5 class='card-title'>".$cita["hora"]."</h5>
                                                <p class='card-text mb-1'>
                                                    <span>Paciente:</span> ".$cita["paciente"]."
                                                </p>
                                                <p class='card-text'>
                                                    <span>Tipo:</span> ".$cita["tipo"]."
                                                </p>
                                            </div>
                                        </div>
                                        ";
                                    }
                                } else {
                                    echo "<p class='text-center'>No tienes citas hoy</p>";
                                }
                            }
                        ?>
                    </div>
                </div>
            </section>

            <!-------------------------------------------------------------------------------- PANEL DIAGNÓSTICO -->
            <section class="col-lg-9 col-md-8 diagnostico">
                <div class="card panel-diagnostico shadow-lg border-0 h-100">
                    <div class="card-header">
                        <h3 class="mb-0">Panel de Diagnóstico</h3>
                    </div>
                    <div class="card-body diagnostico-body">
                        <h4 class="mb-4 text-center">Buscar paciente</h4>
                        <form id="formBuscarPaciente" class="form-diagnostico">
                            <div class="input-group input-group-lg">
                                <input type="text" id="busquedaPaciente" class="form-control" placeholder="DNI o SIP" required>
                                <button class="btn btn-buscar">Buscar</button>
                            </div>
                        </form>
                        <p id="mensajeBusqueda" class="text-danger text-center mt-3"></p>
                        <p class="text-muted mt-4 text-center">
                            Introduce el DNI o SIP del paciente para acceder a su historial médico y realizar el diagnóstico.
                        </p>
                    </div>
                </div>
            </section>
            <!------------------------------------------------------------------------------ MODAL DEL HISTORIAL -->
                <?php
                    include_once("../components/modalDiagnostico.php");
                ?>
        </div>
    </main>
    <!--------------------------------------------------------------------------------------------------- FOOTER -->
    <?php
        include_once("../components/footer.php");
    ?>
    <!----------------------------------------------------------------------------------------------- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="../js/landingPage_Medico.js"></script>
    <script src="../js/modalDiagnostico.js"></script>
</body>
</html>