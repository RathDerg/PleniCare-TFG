<?php
    session_start();
    include_once("./conexionBBDD.php");
    $conexion = null;
    header('Content-Type: application/json');
    try{
        $conexion = new PDO($url, $user, $pass);
		$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dato = $_POST["dato"];

        $sentencia = "SELECT id_paciente, nombre, apellidos, dni, sip 
                        FROM paciente 
                        WHERE dni = :dato OR sip = :dato";

        $sql = $conexion->prepare($sentencia);
        $sql->bindParam(":dato", $dato);
        $sql->execute();

        if($sql->rowCount() > 0){
            $tupla = $sql->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "id" => $tupla["id_paciente"],
                "nombre" => $tupla["nombre"]." ".$tupla["apellidos"]
            ]);
        } else {
            echo json_encode(["error" => true]);
        }
    }catch (PDOException $e) {
        echo "Ha ocurrido un problema con la base de datos o la conexión a ella.<br>- ".$e->getMessage()."<br> Redireccionando...";
        sleep(3);
        header("Location:../index.php");
        exit();
    }