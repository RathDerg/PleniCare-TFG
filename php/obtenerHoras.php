<?php
include_once("conexionBBDD.php");

$fecha = $_POST["fecha"];

try{
    $conexion = new PDO($url, $user, $pass);

    $todas = ["09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30"];

    $sql = $conexion->prepare("SELECT hora FROM pedir_cita WHERE fecha = :fecha");
    $sql->execute([":fecha"=>$fecha]);

    $ocupadas = $sql->fetchAll(PDO::FETCH_COLUMN);

    $disponibles = array_values(array_diff($todas, $ocupadas));

    echo json_encode($disponibles);

}catch(PDOException $e){
    echo json_encode([]);
}