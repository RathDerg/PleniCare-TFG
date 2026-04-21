<?php 
    session_start();
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["formCita"]) && isset($_SESSION["user"])){
        include_once("./conexionBBDD.php");
        try {
            $conexion = new PDO ($url, $user, $pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sentencia = "INSERT INTO pedir_cita (id_medico, id_paciente, fecha, hora, tipo) 
                            VALUES (:medico, :paciente, :fecha, :hora, :tipo)";
            $sql = $conexion -> prepare($sentencia);
            if($_SESSION["tipo"]="paciente"){
                $sql ->bindParam(":medico",$_POST["medico"]);
                $sql ->bindParam(":paciente", $_SESSION["user"]);
            } else{
                $sql ->bindParam(":paciente", $_POST["paciente"]);
                $sql ->bindParam(":medico",$_SESSION["user"]);
            }
            $sql ->bindParam(":fecha", $_POST["fecha"]);
            $sql ->bindParam(":hora", $_POST["hora"]);
            $sql ->bindParam(":tipo",$_POST["tipo"]);
            
            $sql ->execute();

            header("Location:../web/citas.php");

        } catch (PDOException $e) {
            echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
            sleep(3);
            header("Location:../index.php");
            exit();
        }
    }