<?php
    session_start();
    include_once("./conexionBBDD.php");
    $conexion = null;
    if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST["btnFormHistorial"])){
        try{
            $conexion = new PDO($url, $user, $pass);
		    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $id_paciente = $_POST["id_paciente"] ?? "";

            if(empty($id_paciente) && !empty($_POST["busquedaPaciente"])){

                $dato = $_POST["busquedaPaciente"];

                $sqlBuscar = $conexion->prepare("SELECT id_paciente FROM paciente WHERE dni = ? OR sip = ?");
                $sqlBuscar->execute([$dato, $dato]);

                if($sqlBuscar->rowCount() > 0){
                    $row = $sqlBuscar->fetch(PDO::FETCH_ASSOC);
                    $id_paciente = $row["id_paciente"];
                } else {
                    die("Paciente no encontrado");
                }
            }

            if(empty($id_paciente)){
                die("Error: paciente no válido");
            }

            $id_paciente = $_POST["id_paciente"];
            $titulo = $_POST["titulo"];
            $descripcion = $_POST["descripcion"];
            $id_medico = $_SESSION["user"];
            $fecha = date("Y-m-d");

            $medicamentos = "";
            if(isset($_POST["medicamentos"]) && !empty($_POST["medicamentos"])){
                $medicamentos = implode(", ", $_POST["medicamentos"]);
            }

            $sql = $conexion->prepare("INSERT INTO historial_medico 
            (titulo, fecha, descripcion, medicamento, id_paciente, id_medico)
            VALUES (:titulo, :fecha, :descripcion, :medicamentos, :paciente, :medico)");

            $sql->execute([
                ":titulo"=>$titulo,
                ":fecha"=>$fecha,
                ":descripcion"=>$descripcion,
                ":medicamentos"=>$medicamentos,
                ":paciente"=>$id_paciente,
                ":medico"=>$id_medico
            ]);
            $id_historial = $conexion->lastInsertId();

            if(isset($_FILES["archivos"]) && !empty($_FILES["archivos"]["name"][0])){
                foreach($_FILES["archivos"]["tmp_name"] as $key => $tmp){

                    $nombre = $_FILES["archivos"]["name"][$key];
                    move_uploaded_file($tmp, "../uploads/".$nombre);

                    $conexion->prepare("INSERT INTO archivo_medico 
                    (archivo, id_historial) VALUES (?,?)")
                    ->execute([$nombre, $id_historial]);
                }
            }
        }catch (PDOException $e) {
            echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
            sleep(3);
            header("Location:../index.php");
            exit();
        }
        
        header("Location: ../web/landingPage_Medico.php");
        exit();

    } else {
        echo "Redireccionando...";
        sleep(3);
        header("Location:../index.php");
        exit();
    }