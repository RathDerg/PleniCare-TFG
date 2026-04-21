<?php
session_start();

if(!isset($_SESSION["user"])){
    exit("Acceso denegado");
}
require '../vendor/autoload.php';
use Dompdf\Dompdf;

include_once("./conexionBBDD.php");

try {
    $conexion = new PDO ($url, $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión");
}

$id = $_GET["id"];


$sentencia = "SELECT h.fecha, h.titulo, h.descripcion, h.medicamento,
                     m.nombre, m.apellidos, m.especialidad
              FROM historial_medico h
              JOIN medico m ON h.id_medico = m.id_medico
              WHERE h.id_historial = :id";

$sql = $conexion->prepare($sentencia);
$sql->bindParam(":id", $id);
$sql->execute();
$hist = $sql->fetch(PDO::FETCH_ASSOC);

$sentenciaArch = "SELECT archivo, resumen 
                  FROM archivo_medico 
                  WHERE id_historial = :id";

$sqlArch = $conexion->prepare($sentenciaArch);
$sqlArch->bindParam(":id", $id);
$sqlArch->execute();

$html = "
<h1>Historial Médico</h1>
<hr>

<h3>{$hist["titulo"]}</h3>
<p><strong>Fecha:</strong> {$hist["fecha"]}</p>
<p><strong>Médico:</strong> {$hist["nombre"]} {$hist["apellidos"]}</p>
<p><strong>Especialidad:</strong> {$hist["especialidad"]}</p>

<h4>Descripción</h4>
<p>{$hist["descripcion"]}</p>

<h4>Medicación</h4>
<ul>";

$meds = explode(",", $hist["medicamento"]);
foreach($meds as $med){
    $html .= "<li>".trim($med)."</li>";
}

$html .= "</ul>";

if($sqlArch->rowCount() > 0){
    $html .= "<h4>Archivos adjuntos</h4><ul>";
    while($a = $sqlArch->fetch(PDO::FETCH_ASSOC)){
        $html .= "<li>{$a["resumen"]}</li>";
    }
    $html .= "</ul>";
}

$html .= "<hr><p style='font-size:12px;'>PleniCare - Documento generado automáticamente</p>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4");
$dompdf->render();

if(isset($_GET["download"])){
    $dompdf->stream("historial.pdf", ["Attachment" => true]); // descarga
} else {
    $dompdf->stream("historial.pdf", ["Attachment" => false]); // abrir en navegador
}