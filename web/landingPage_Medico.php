<?php
    session_start();
    if(!isset($_SESSION["login"]) || $_SESSION["tipo"] != "medico"){
        header("Location:../index.php");
        exit();
    }
    include_once("../php/conexionBBDD.php");
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
    <link rel="stylesheet" href="../css/styleSobreNosotros.css">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php
        include_once("../components/headerMedico.php");
    ?>
    <!----------------------------------------------------------------------------------------------------- MAIN -->
    <main class="container-fluid my-4">
        <div class="row">
            <!----------------------------------------------------------------------------------- PANEL DE CITAS -->
            <section class="col-lg-3 col-md-4 mb-4">
                <div class="card panel-citas shadow h-100">
                    <div class="card-header text-center">
                        <h4 class="mb-0">Citas de Hoy</h4>
                    </div>
                    <!------------------------------- ESTO HAY QUE CAMBIARLO, CUANDO IMPLEMENTE LA FUNCIONALIDAD -->
                    <!------------ CON UN FOREACH, TRAIDO DE LA BBDD, CON EL FORMATO DE CARDS DE LA PÁGINA CITAS -->
                    <div class="card-body citas-scroll">
                        <div class="card cita-card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">10:00</h5>
                                <p class="card-text mb-1"><span>Paciente:</span> Laura Pérez</p>
                                <p class="card-text"><span>Tipo:</span> Consulta general</p>
                            </div>
                        </div>

                        <div class="card cita-card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">10:30</h5>
                                <p class="card-text mb-1"><span>Paciente:</span> Miguel Gómez</p>
                                <p class="card-text"><span>Tipo:</span> Revisión</p>
                            </div>
                        </div>

                        <div class="card cita-card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">11:00</h5>
                                <p class="card-text mb-1"><span>Paciente:</span> Ana Martínez</p>
                                <p class="card-text"><span>Tipo:</span> Consulta general</p>
                            </div>
                        </div>
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
                        <form class="form-diagnostico">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control">
                                <button class="btn btn-buscar">Buscar</button>
                            </div>
                        </form>
                        <p class="text-muted mt-4 text-center">
                            Introduce el nombre o SIP del paciente para acceder a su historial médico y realizar el diagnóstico.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <!--------------------------------------------------------------------------------------------------- FOOTER -->
    <?php
        @include_once("../components/footer.php");
    ?>
    <!----------------------------------------------------------------------------------------------- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
</body>
</html>