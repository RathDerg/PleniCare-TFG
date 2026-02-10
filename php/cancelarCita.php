<?php
    include_once("./conexionBBDD.php");
    if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["cancelar"])) {
        $idPaciente = $_POST["paciente"];
        $idMedico = $_POST["medico"];
        $fecha = $_POST["fecha"];
        $hora = $_POST["hora"];
        try{
            $conexion = new PDO ($url, $user, $pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sentencia = "DELETE FROM pedir_cita WHERE id_medico = ? AND id_paciente = ? AND fecha = ? AND hora = ?";
            $sql = $conexion -> prepare($sentencia);
            $sql -> execute([$idMedico,$idPaciente,$fecha,$hora]);
            if ($sql->rowCount()>0){
                header("Location:../web/citas.php");
                exit();
            }
        }catch (PDOException $e){
            echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
            sleep(3);
            header("Location:../index.html");
            exit();
        } 
    } else {
        header("Location:../index.html");
        exit();
    }
    