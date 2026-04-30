<?php
include_once("conexionBBDD.php");

$especialidad = $_POST["especialidad"];
$fecha = $_POST["fecha"];
$hora = $_POST["hora"];

try{
    $conexion = new PDO($url, $user, $pass);

    $sql = $conexion->prepare("
        SELECT m.id_medico as id, CONCAT(m.nombre, ' ', m.apellidos) as nombre
        FROM medico m
        WHERE m.especialidad = :esp
        AND NOT EXISTS (
            SELECT 1
            FROM pedir_cita pc
            WHERE pc.id_medico = m.id_medico
            AND pc.fecha = :fecha
            AND pc.hora = :hora
        )
        ORDER BY nombre ASC
    ");

    $sql->execute([
        ":esp"=>$especialidad,
        ":fecha"=>$fecha,
        ":hora"=>$hora
    ]);

    echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC));

}catch(PDOException $e){
    echo json_encode([]);
}