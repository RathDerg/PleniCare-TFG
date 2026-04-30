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
    <link rel="icon" href="../media/simbolo.png">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php
        include_once("../components/headerPaciente.php");
    ?>
    <!----------------------------------------------------------------------------------------------------- MAIN -->
    <main class="container-fluid paciente-main">
        <div class="row h-100">
            <!-------------------------------------------------------------------------------------------- CITAS -->
            <div class="col-lg-8 d-flex flex-column">
                <section class="citas-section d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="titulo-seccion">Próximas Citas</h3>
                        <a href="./citas.php" class="btn btn-pleni">Solicitar Cita</a>
                    </div>

                    <div class="row g-3 citas">
                        <?php
                            $sentencia = "SELECT pc.fecha, pc.hora, m.nombre, m.apellidos, m.especialidad
                                        FROM pedir_cita pc
                                        JOIN medico m ON pc.id_medico = m.id_medico
                                        WHERE pc.id_paciente = :id
                                        AND pc.fecha >= CURDATE()
                                        ORDER BY pc.fecha ASC, pc.hora ASC
                                        LIMIT 3";
                            $sql = $conexion->prepare($sentencia);
                            $sql->bindParam(":id", $_SESSION["user"]);
                            $sql->setFetchMode(PDO::FETCH_ASSOC);
                            if($sql->execute()){
                                if($sql->rowCount() > 0){
                                    while($cita = $sql->fetch()){
                                        echo "
                                            <div class='col-md-4'>
                                                <div class='card cita-card'>
                                                    <div class='card-body'>
                                                        <h5 class='card-title'>{$cita["especialidad"]}</h5>
                                                        <p class='card-text'>Dr/a. {$cita["nombre"]} {$cita["apellidos"]}</p>
                                                        <p class='card-text'>{$cita["fecha"]} · {$cita["hora"]}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        ";
                                    }
                                } else {
                                    echo "<p class='text-center'>No tienes citas próximas.</p>";
                                }
                            }
                            ?>
                        </div>
                </section>

                <div class="separador"></div>

            <!---------------------------------------------------------------------------------------- HISTORIAL -->
                <section class="historial-section">
                    <h3 class="titulo-seccion mb-3">Historial Médico Reciente</h3>
                    <div class="row g-3">
                        <?php
                        $sentencia = "SELECT h.id_historial, h.fecha, h.titulo, h.descripcion, h.medicamento,
                                        m.nombre, m.apellidos, m.especialidad, COUNT(a.id_prueba) as tiene_archivos
                                    FROM historial_medico h
                                    JOIN medico m ON h.id_medico = m.id_medico
                                    LEFT JOIN archivo_medico a ON h.id_historial = a.id_historial
                                    WHERE h.id_paciente = :id
                                    GROUP BY h.id_historial
                                    ORDER BY h.fecha DESC
                                    LIMIT 3";

                        $sql = $conexion->prepare($sentencia);
                        $sql->bindParam(":id", $_SESSION["user"]);
                        $sql->setFetchMode(PDO::FETCH_ASSOC);

                        if($sql->execute()){
                            if($sql->rowCount() > 0){
                                while($hist = $sql->fetch()){
                                    $fechaFormateada = date("d M Y", strtotime($hist["fecha"]));
                                    $icono = ($hist["tiene_archivos"] > 0) ? "bi-file-earmark-medical" : "bi-info-circle";
                                    echo "
                                        <div class='col-md-4'>
                                            <div class='historial-card'>
                                                <div class='historial-info'>
                                                    <span class='fecha'>{$fechaFormateada}</span>
                                                    <h6>{$hist["titulo"]}</h6>
                                                    <p>Dr/a. {$hist["nombre"]} {$hist["apellidos"]}</p>
                                                    <small>{$hist["especialidad"]}</small>
                                                </div>
                                                <div class='historial-icon'>
                                                    <i class='bi {$icono}'
                                                        data-fecha='{$hist['fecha']}'
                                                        data-titulo='{$hist['titulo']}'
                                                        data-descripcion='{$hist['descripcion']}'
                                                        data-medicamento='{$hist['medicamento']}'
                                                        data-medico='{$hist['nombre']} {$hist['apellidos']}'
                                                        data-especialidad='{$hist['especialidad']}'></i>
                                                </div>
                                            </div>
                                        </div>
                                    ";
                                }
                            } else {
                                echo "<p class='text-center'>No hay historial médico reciente.</p>";
                            }
                        }
                        ?>
                    </div>
                </section>
            </div>
            <!------------------------------------------------------------------------------------------- TABLÓN -->
            <aside class="col-lg-4 tablon-section">
                <h3 class="titulo-seccion mb-3">Tablón de Anuncios</h3>
                <div class="tablon-scroll">
                    
                    <div class="card anuncio-card">
                        <div class="card-header anuncio-header">
                            <img src="../media/retratoMedico.jpg" class="doctor-img">
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
                            <img src="../media/retratoMedica.jpg" class="doctor-img">
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

    <!---------------------------------------------------------------------------------------- MODAL HISTORIALES -->
        <div class="modal fade" id="modalHistorialInfo" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Detalle del Historial</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                        <p><strong>Título:</strong> <span id="modalTitulo"></span></p>
                        <p><strong>Médico:</strong> <span id="modalMedico"></span></p>
                        <p><strong>Especialidad:</strong> <span id="modalEspecialidad"></span></p>

                        <hr>

                        <h6>Descripción</h6>
                        <p id="modalDescripcion"></p>

                        <h6>Medicamentos</h6>
                        <p id="modalMedicamento"></p>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <!--------------------------------------------------------------------------------------------------- FOOTER -->
    <?php
        @include_once("../components/footer.php");
    ?>
    <!----------------------------------------------------------------------------------------------- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="../js/landingPage_Paciente.js"></script>
</body>
</html>