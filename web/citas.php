<?php
    session_start();
    if(!isset($_SESSION["login"]) || ($_SESSION["tipo"]!="paciente" && $_SESSION["tipo"]!="medico")){
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
    <title>Citas - PleniCare</title>
    <link rel="stylesheet" href="../css/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/styleCitas.css">
    <link rel="stylesheet" href="../css/styleHeaderFooter.css">
    <link rel="stylesheet" href="../css/styleSobreNosotros.css">
    <link rel="icon" href="../media/simbolo.png">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php
            if(isset($_SESSION["tipo"]) && $_SESSION["tipo"]=="paciente"){
                include_once("../components/headerPaciente.php");
            }else if(isset($_SESSION["tipo"]) && $_SESSION["tipo"]=="medico"){
                include_once("../components/headerMedico.php");
            }
    ?>
    <main class="container">
        <!--------------------------------------------------------------------------------------- CARDS DE CITAS -->
        <section class="row my-4">
            <?php
            /* ----------------------------------------------------------------------------- CARDS PARA PACIENTES */
            if($_SESSION["tipo"]=="paciente"){
                $sentencia = "SELECT pc.hora as hora, pc.fecha as fecha, pc.tipo as tipo, CONCAT(m.nombre, ' ', m.apellidos) as nombre_completo, m.especialidad as especialidad, m.id_medico as medico
                                from pedir_cita pc 
                                join paciente p on pc.id_paciente = p.id_paciente
                                join medico m on pc.id_medico = m.id_medico 
                                where p.id_paciente = :id
                                order by fecha asc";
                $sql = $conexion ->prepare($sentencia);
                $sql -> bindParam(":id", $_SESSION["user"]);
                $sql -> setFetchMode(PDO::FETCH_ASSOC);
                if($sql->execute()){
                    if($sql->rowCount()>0){
                        while($tupla = $sql -> fetch()){
                            echo "<div class='col-md-4 col-sm-6 col-12 mb-4'>
                                <div class='card cita-card h-100'>

                                    <div class='card-header text-center'>
                                        <h5>".$tupla["especialidad"]."</h5>
                                    </div>

                                    <div class='card-body text-center'>
                                        <h3 class='hora'>".$tupla["hora"]."</h3>
                                        <p class='fecha'>".$tupla["fecha"]."</p>
                                        <span class='tipo'>".$tupla["tipo"]."</span>
                                        <p class='medico'>".$tupla["nombre_completo"]."</p>
                                    </div>

                                    <div class='card-footer'>
                                        <i class='bi bi-trash3-fill icono'
                                            data-rol='paciente'
                                            data-fecha='".$tupla["fecha"]."'
                                            data-hora='".$tupla["hora"]."'
                                            data-nombre='".$tupla["nombre_completo"]."'
                                            data-medico='".$tupla["medico"]."'>
                                        </i>
                                    </div>

                                </div>
                            </div>";
                        }
                    }else {
                        echo "<h4 class=\"text-center my-4\">No hay citas pendientes.</h4>";
                    }
                }
            /* ----------------------------------------------------------------------------- CARDS PARA MEDICOS */
            } else{
                $sentencia = "SELECT pc.hora as hora, pc.fecha as fecha, pc.tipo as tipo, CONCAT(p.nombre, ' ', p.apellidos) as nombre_completo, m.especialidad as especialidad, p.id_paciente as paciente
                                from pedir_cita pc 
                                join paciente p on pc.id_paciente = p.id_paciente
                                join medico m on pc.id_medico = m.id_medico 
                                where m.id_medico = :id
                                order by fecha asc";
                $sql = $conexion ->prepare($sentencia);
                $sql -> bindParam(":id", $_SESSION["user"]);
                $sql -> setFetchMode(PDO::FETCH_ASSOC);
                if($sql->execute()){
                    if($sql->rowCount()>0){
                        while($tupla = $sql -> fetch()){
                            echo "<div class='col-md-4 col-sm-6 col-12 mb-4'>
                                <div class='card cita-card h-100'>

                                    <div class='card-header text-center'>
                                        <h5>".$tupla["especialidad"]."</h5>
                                    </div>

                                    <div class='card-body text-center'>
                                        <h3 class='hora'>".$tupla["hora"]."</h3>
                                        <p class='fecha'>".$tupla["fecha"]."</p>
                                        <span class='tipo'>".$tupla["tipo"]."</span>
                                        <p class='paciente'>".$tupla["nombre_completo"]."</p>
                                    </div>

                                    <div class='card-footer'>
                                        <i class='bi bi-trash3-fill icono'
                                            data-rol='medico'
                                            data-fecha='".$tupla["fecha"]."'
                                            data-hora='".$tupla["hora"]."'
                                            data-nombre='".$tupla["nombre_completo"]."'
                                            data-paciente='".$tupla["paciente"]."'>
                                        </i>
                                    </div>

                                </div>
                            </div>";
                        }
                    }else {
                        echo "<h4 class=\"text-center my-4\">No hay citas pendientes.</h4>";
                    }
                }
            }
            ?>
        </section>
        <section class="d-flex justify-content-center mb-4">
            <button type="button" class="btn botonSolicitar" id="btnMSolicitar" data-bs-toggle="modal" data-bs-target="#modalSolicitar">
                Solicitar Cita
            </button>
        </section>

        <div class="separador"></div>
        <!-------------------------------------------------------------------------- MODAL DE SOLICITUD DE CITAS -->
        <section class="modal fade" id="modalSolicitar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Solicitar Cita</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" method="POST" action="./formularioCita.php" novalidate>
                            <div class="col-md-4">
                                <label for="dia" class="form-label">Día</label>
                                <select name="dia" required>
                                    <option value="" disabled selected>---</option>
                                    <?php
                                        try{
                                            $sentencia = "SELECT DISTINCT fecha FROM cita";
                                            $sql = $conexion -> prepare($sentencia);
                                            $sql -> setFetchMode(PDO::FETCH_ASSOC);
                                            if($sql->execute()){
                                                while($tupla = $sql -> fetch()){
                                                    echo "<option value=\"".$tupla["fecha"]."\">".$tupla["fecha"]."</option>";
                                                }
                                            }
                                        }catch(PDOException $e){
                                            echo "Ha ocurrido un problema al realizar la búsqueda en la base de datos, contacte con administrador.<br>".$e->getMessage();
                                        }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Seleccione un día.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="hora" class="form-label">Hora</label>
                                <select name="hora" required>
                                    <option value="" disabled selected>---</option>
                                    <option value="09:00">09:00</option>
                                    <option value="09:30">09:30</option>
                                    <option value="10:00">10:00</option>
                                    <option value="10:30">10:30</option>
                                    <option value="11:00">11:00</option>
                                    <option value="11:30">11:30</option>
                                    <option value="12:00">12:00</option>
                                    <option value="12:30">12:30</option>
                                    <option value="13:00">13:00</option>
                                    <option value="13:30">13:30</option>
                                </select>
                                <div class="invalid-feedback">
                                    Seleccione una hora.
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" name="buscar" type="submit">Buscar cita</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!------------------------------------------------------------------------------ MODAL DE CANCELAR CITAS -->
        <section class="modal fade" id="modalCancelar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Cancelar Cita</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <div class="modal-body">
                    <h3>¿Está seguro/a de cancelar la cita?</h3>
                    <p id="infoCita" class="border border-warning bg-warning-subtle p-3 mt-3 text-center"></p>
                    <form id="cancelar" method="POST" action="../php/cancelarCita.php">
                        <?php
                            if($_SESSION["tipo"]=="paciente"){
                                echo "<input type='hidden' name='paciente' value='".$_SESSION['user']."'>
                                <input type='hidden' name='medico' id='cancelarMedico' value=''>";
                            }else {
                                echo "<input type='hidden' name='medico' value='".$_SESSION['user']."'>
                                <input type='hidden' name='paciente' id='cancelarPaciente' value=''>";
                            }
                        ?>
                        <input type="hidden" name="fecha" id="cancelarFecha" value="">
                        <input type="hidden" name="hora" id="cancelarHora" value="">
                    </form>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger" name="cancelar" form="cancelar">Sí, quiero cancelar</button>
                    </div>
                </div>
            </div>
        </section>
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