<?php
    if($_SERVER["REQUEST_METHOD"]!="POST" && !isset($_POST["buscar"])){
        header("Location:../index.html");
        exit();
    }
    include_once("../php/conexionBBDD.php");
    session_start();
    $conexion = null;
    $fecha = $_POST["fecha"];
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
    <link rel="icon" href="../media/simbolo.png">
</head>
<body>
    <!--------------------------------------------------------------------------------------------------- HEADER -->
    <?php 
        if($_SESSION["tipo"]=="paciente"){
            include_once("../components/headerPaciente.php");
        }else if($_SESSION["tipo"]=="medico"){
            include_once("../components/headerMedico.php");
        }
    ?>
    <!----------------------------------------------------------------------------------------------- FORMULARIO -->
    <main>
    <!------------------------------------------------------------------------------------ PACIENTE BUSCA MEDICO -->
        <?php 
            if($_SESSION["tipo"]=="paciente"){
        ?>
        <form class="row g-3 needs-validation m-5" method="POST" action="../php/registrarCita.php" novalidate>
            <div class="col-md-5 position-relative">
                <label for="fecha" class="form-label">Día</label>
                <input type="text" class="form-control" id="dia" name="fecha" value="<?php echo $fecha ?>" readonly>
            </div>
            <div class="col-md-5 position-relative">
                <label for="hora" class="form-label">Hora</label>
                <input type="text" class="form-control" name="hora" value="<?php echo $hora ?>" readonly>
            </div>
            
            <div class="col-md-4 position-relative">
                <label class="form-label">Especialidad</label>
                <select class="form-select" name="especialidad" id="especialidad" required>
                    <option selected disabled value="">---</option>
                    <?php
                        try{
                            $sql = $conexion->query("SELECT DISTINCT especialidad FROM medico");
                            while($tupla = $sql->fetch(PDO::FETCH_ASSOC)){
                                echo "<option value='".$tupla["especialidad"]."'>".$tupla["especialidad"]."</option>";
                            }
                        } catch (PDOException $e) {
                            echo "<option>Error al cargar</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="col-md-4 position-relative">
                <label for="medico" class="form-label">Médica / Médico</label>
                <select class="form-select" name="medico" id="medico" required>
                    <option selected disabled value="">Escoja una especialidad</option>
                </select>
            </div>

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
        <?php
            } else {
        ?>

        <!---------------------------------------------------------------------------- MEDICO BUSCA PACIENTE -->
        <form class="row g-3 needs-validation m-5" method="POST" action="../php/registrarCita.php" novalidate>
            <div class="col-md-5 position-relative">
                <label for="fecha" class="form-label">Día</label>
                <input type="text" class="form-control" name="fecha" value="<?php echo $fecha ?>" readonly>
            </div>
            <div class="col-md-5 position-relative">
                <label for="hora" class="form-label">Hora</label>
                <input type="text" class="form-control" name="hora" value="<?php echo $hora ?>" readonly>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Paciente (DNI o SIP)</label>
                <input type="text" id="busquedaPacienteCita" class="form-control" placeholder="Buscar paciente..." required>
                <div id="resultadosPacienteCita"></div>
            </div>

            <input type="hidden" name="paciente" id="idPacienteCita">

            <div class="col-md-4" id="tipo" style="display: none;">
                <label class="form-label">Tipo de cita</label>
                <select class="form-select" name="tipo" required>
                    <option selected disabled value="">---</option>
                    <option value="TELEFONICA">Telefónica</option>
                    <option value="PRESENCIAL">Presencial</option>
                    <option value="URGENCIA">Urgencia</option>
                </select>
            </div>

            <div class="col-12" id="boton" style="display: none;">
                <button class="btn btn-primary" name="formCita">Asignar cita</button>
            </div>
        </form>
        <?php
            }
        ?>
    </main>
    <!----------------------------------------------------------------------------------------  FOOTER    -->
        <?php @include_once("../components/footer.php"); ?>
    <!---------------------------------------------------------------------------------------- JAVASCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="../js/formularioCita.js"></script>
    <?php
        if($_SESSION["tipo"]=="medico"){
    ?>
        <script>
            document.getElementById("busquedaPacienteCita").addEventListener("input", function(){

            let dato = this.value.trim();
            const contenedor = document.getElementById("resultadosPacienteCita");

            if(dato.length < 3){
                contenedor.innerHTML = "";
                return;
            }

            fetch("../php/buscarPaciente.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "dato=" + encodeURIComponent(dato)
            })
            .then(res => res.json())
            .then(data => {

                contenedor.innerHTML = "";

                if(data.error){
                    contenedor.innerHTML = `<div class="text-danger">No encontrado</div>`;
                    return;
                }

                contenedor.innerHTML = `
                    <div class="p-2 border rounded resultado-item"
                        data-id="${data.id}"
                        data-nombre="${data.nombre}">
                        ${data.nombre}
                    </div>
                `;

                document.querySelector(".resultado-item").addEventListener("click", function(){
                    document.getElementById("busquedaPacienteCita").value = this.dataset.nombre;
                    document.getElementById("idPacienteCita").value = this.dataset.id;
                    document.getElementById("tipo").style.display = "block";
                    document.getElementById("boton").style.display = "block";
                    contenedor.innerHTML = "";
                });

            });
        });
        </script>
    <?php
        }
    ?>
</body>
</html>