<?php 
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["formCita"])){
        session_start();
        include_once("./conexionBBDD.php");
        try {
            $conexion = new PDO ($url, $user, $pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sentencia = "INSERT INTO pedir_cita (id_medico, id_paciente, fecha, hora, tipo) 
                            VALUES (:medico, :paciente, :fecha, :hora, :tipo)";
            $sql = $conexion -> prepare($sentencia);
            $sql ->bindParam(":medico",$_POST["medico"]);
            $sql ->bindParam(":paciente", $_SESSION["user"]);
            $sql ->bindParam(":fecha", $_POST["fecha"]);
            $sql ->bindParam(":hora", $_POST["hora"]);
            $sql ->bindParam(":tipo",$_POST["tipo"]);
            
            $sql ->execute();

            header("Location:../web/citas.php");

        } catch (PDOException $e) {
            echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
            sleep(3);
            header("Location:../index.html");
            exit();
        }
    }