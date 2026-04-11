<?php
    session_start();
    if(!isset($_SESSION["login"]) || $_SESSION["tipo"]!="paciente"){
        header("Location:../index.html");
        exit();
    }
    include_once("../php/conexionBBDD.php");
    try {
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
    <title>Mi Área - PleniCare</title>
    <link rel="stylesheet" href="../css/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styleLPPaciente.css">
    <link rel="stylesheet" href="../css/styleHeaderFooter.css">
    <link rel="stylesheet" href="../css/styleSobreNosotros.css">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php
        include_once("../components/headerPaciente.php");
    ?>
    <!----------------------------------------------------------------------------------------------------- MAIN -->
    <main class="container-fluid paciente-main">
        <div class="row h-100">
            <div class="col-lg-8 d-flex flex-column">
                <section class="citas-section d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="titulo-seccion">Próximas Citas</h3>
                        <a href="./citas.php" class="btn btn-pleni">Solicitar Cita</a>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card cita-card">
                                <div class="card-body">
                                    <h5 class="card-title">Medicina General</h5>
                                    <p class="card-text">Dr. Carlos Medina</p>
                                    <p class="card-text">12 Marzo · 10:30</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card cita-card">
                                <div class="card-body">
                                    <h5 class="card-title">Dermatología</h5>
                                    <p class="card-text">Dra. Laura Gómez</p>
                                    <p class="card-text">18 Marzo · 09:30</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="separador"></div>
                
                <section class="historial-section">
                    <h3 class="titulo-seccion mb-3">Historial Médico Reciente</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="historial-card">
                                <div class="historial-info">
                                    <span class="fecha">04 Mar 2026</span>
                                    <h6>Revisión General</h6>
                                    <p>Dr. Carlos Medina</p>
                                    <small>Medicina General</small>
                                </div>
                                <div class="historial-icon">i</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="historial-card">
                                <div class="historial-info">
                                    <span class="fecha">20 Feb 2026</span>
                                    <h6>Análisis de Sangre</h6>
                                    <p>Dra. Elena Ruiz</p>
                                    <small>Laboratorio</small>
                                </div>
                                <div class="historial-icon">i</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="historial-card">
                                <div class="historial-info">
                                    <span class="fecha">12 Feb 2026</span>
                                    <h6>Consulta Dermatológica</h6>
                                    <p>Dra. Laura Gómez</p>
                                    <small>Dermatología</small>
                                </div>
                                <div class="historial-icon">i</div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="col-lg-4 tablon-section">
                <h3 class="titulo-seccion mb-3">Tablón de Anuncios</h3>
                <div class="tablon-scroll">
                    
                    <div class="card anuncio-card">
                        <div class="card-header anuncio-header">
                            <img src="../media/doctor1.jpg" class="doctor-img">
                            <div>
                                <strong>Dr. Carlos Medina</strong>
                                <small>Medicina General</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Se recomienda la vacunación contra la gripe estacional para pacientes mayores de 60 años.</p>
                        </div>
                    </div>

                    <div class="card anuncio-card">
                        <div class="card-header anuncio-header">
                            <img src="../media/doctor2.jpg" class="doctor-img">
                            <div>
                                <strong>Dra. Laura Gómez</strong>
                                <small>Dermatología</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Durante primavera aumentan las alergias cutáneas. Consulte si presenta irritaciones.</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>
    <!--------------------------------------------------------------------------------------------------- FOOTER -->
    <?php
        @include_once("../components/footer.php");
    ?>
    <!----------------------------------------------------------------------------------------------- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="../js/citasJS.js"></script>
</body>
</html>