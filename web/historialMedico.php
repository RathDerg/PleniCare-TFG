<?php
    session_start();
    $_SESSION["user"]="1";
    /*
    if(!isset($_SESSION("loggin"))){
        header("Location:../index.html");
        exit();
    }*/
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
    <title>Citas - PleniCare</title>
    <link rel="stylesheet" href="../css/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styleHistorial.css">
    <link rel="stylesheet" href="../css/styleHeaderFooter.css">
    <link rel="stylesheet" href="../css/styleSobreNosotros.css">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php /* <-- QUITAR UNA VEZ SE HAGA EL LOG IN (ACUÉRDATE JOSE)
        if($_SESSION("tipo")=="paciente"){
            include_once("../components/headerPaciente.php");
        }else if($_SESSION("tipo")=="medico"){
            include_once("../components/headerMedico.php");
        }else {
            include_once("../components/header.php");
            }
        */
        include_once("../components/headerPaciente.php"); //<-- QUITAR UNA VEZ SE HAGA EL LOG IN (ACUÉRDATE JOSE)
    ?>
    <!----------------------------------------------------------------------------------------------------- MAIN -->
    <main class="container historial-main">
        <h2 class="titulo-historial mb-4">Historial Médico</h2>
        <details class="historial-item">
            <summary class="historial-summary">
                <div class="historial-info">
                    <span class="historial-fecha">12 Marzo 2026</span>
                    <h5 class="historial-titulo">Revisión General</h5>
                    <p class="historial-medico">Dr. Carlos Medina · Medicina General</p>
                </div>
                <div class="historial-acciones">
                    <a href="../historialPDF/consulta12.pdf" target="_blank" class="icono">📄</a>
                    <a href="../historialPDF/consulta12.pdf" download class="icono">⬇</a>
                </div>
            </summary>

            <div class="historial-detalle">
                <h6>Información de la consulta</h6>
                <p>
                    El paciente acudió para una revisión general. No se detectaron anomalías
                    importantes. Se recomienda mantener hábitos saludables y repetir análisis
                    dentro de 6 meses.
                </p>
                <h6>Medicación prescrita</h6>
                <ul>
                    <li>Paracetamol 500mg · 1 cada 8h si dolor</li>
                    <li>Vitamina D · 1 cápsula diaria</li>
                </ul>
                <h6>Archivos adicionales</h6>
                <div class="archivos-extra">
                    <a href="../archivos/radiografia1.jpg" target="_blank" class="archivo">Radiografía Torácica</a>
                    <a href="../archivos/analitica.pdf" target="_blank" class="archivo">Analítica de Sangre</a>
                </div>
            </div>
        </details>

        <details class="historial-item">
            <summary class="historial-summary">
                <div class="historial-info">
                    <span class="historial-fecha">20 Febrero 2026</span>
                    <h5 class="historial-titulo">Consulta Dermatológica</h5>
                    <p class="historial-medico">Dra. Laura Gómez · Dermatología</p>
                </div>
                <div class="historial-acciones">
                    <a href="../historialPDF/consulta13.pdf" target="_blank" class="icono">📄</a>
                    <a href="../historialPDF/consulta13.pdf" download class="icono">⬇</a>
                </div>
            </summary>

            <div class="historial-detalle">
                <h6>Información de la consulta</h6>
                <p>
                    El paciente presenta irritación cutánea leve debido a alergia estacional.
                </p>
                <h6>Medicación prescrita</h6>
                <ul>
                    <li>Crema antihistamínica · Aplicar 2 veces al día</li>
                </ul>
            </div>
        </details>
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