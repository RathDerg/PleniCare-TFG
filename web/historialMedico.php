<?php
    session_start();
    if(!isset($_SESSION["login"])){
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
    <link rel="stylesheet" href="../css/styleHistorial.css">
    <link rel="stylesheet" href="../css/styleHeaderFooter.css">
    <link rel="stylesheet" href="../css/styleSobreNosotros.css">
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
    <!----------------------------------------------------------------------------------------------------- MAIN -->
    <main class="container historial-main">
        <h2 class="titulo-historial mb-4">Historial Médico</h2>
            <?php
                $sentencia = "SELECT h.id_historial, h.fecha, h.titulo, h.descripcion, h.medicamento,
                                    m.nombre, m.apellidos, m.especialidad
                            FROM historial_medico h
                            JOIN medico m ON h.id_medico = m.id_medico
                            WHERE h.id_paciente = :id
                            ORDER BY h.fecha DESC";

                $sql = $conexion->prepare($sentencia);
                $sql->bindParam(":id", $_SESSION["user"]);
                $sql->setFetchMode(PDO::FETCH_ASSOC);
                if($sql->execute()){
                    if($sql->rowCount() > 0){
                        while($hist = $sql->fetch()){
                            $fechaFormateada = date("d F Y", strtotime($hist["fecha"]));
                            echo "
                            <details class='historial-item'>
                                <summary class='historial-summary'>
                                    <div class='historial-info'>
                                        <span class='historial-fecha'>{$fechaFormateada}</span>
                                        <h5 class='historial-titulo'>{$hist["titulo"]}</h5>
                                        <p class='historial-medico'>
                                            Dr/a. {$hist["nombre"]} {$hist["apellidos"]} · {$hist["especialidad"]}
                                        </p>
                                    </div>

                                    <div class='historial-acciones'>
                                        <a href='../php/generarPDF.php?id={$hist["id_historial"]}' target='_blank' class='icono'>📄</a>
                                        
                                        <a href='../php/generarPDF.php?id={$hist["id_historial"]}&download=1' target='_blank'  class='icono'>⬇</a>
                                    </div>
                                </summary>

                                <div class='historial-detalle'>
                                    <h6>Información de la consulta</h6>
                                    <p>{$hist["descripcion"]}</p>";
                            if(!is_null($hist["medicamento"])){
                                    echo "<h6>Medicación prescrita</h6>
                                          <ul>";
                                    $medicamentos = explode(",", $hist["medicamento"]);
                                    foreach($medicamentos as $medicamento){
                                        echo "<li>- ".trim($medicamento)."</li>";
                                    }
                                    echo "</ul>";
                            }
                            $sentenciaArchivos = "SELECT archivo, resumen 
                                                FROM archivo_medico 
                                                WHERE id_historial = :id_historial";

                            $sqlArchivos = $conexion->prepare($sentenciaArchivos);
                            $sqlArchivos->bindParam(":id_historial", $hist["id_historial"]);
                            $sqlArchivos->setFetchMode(PDO::FETCH_ASSOC);

                            if($sqlArchivos->execute() && $sqlArchivos->rowCount() > 0){

                                echo "<h6>Archivos adicionales</h6>
                                    <div class='archivos-extra'>";

                                while($archivo = $sqlArchivos->fetch()){
                                    echo "
                                    <a href='../archives{$archivo["archivo"]}' target='_blank' class='archivo'>
                                        {$archivo["resumen"]}
                                    </a>";
                                }
                                echo "</div>";
                            }

                            echo "
                                </div>
                            </details>";
                        }

                    } else {
                        echo "<p class='text-center'>No hay historial médico disponible.</p>";
                    }
                }
            ?>
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