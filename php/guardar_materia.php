<?php
include 'conexion.php';

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    // Leer datos del cuerpo de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['id_usuario'], $data['nombre_materia'])) {
        throw new Exception("Datos incompletos");
    }

    // Obtener datos
    $id_usuario = $data['id_usuario'];
    $nombre_materia = $data['nombre_materia'];
    $primer_parcial = $data['primer_parcial'] ?? null;
    $segundo_parcial = $data['segundo_parcial'] ?? null;
    $tercer_parcial = $data['tercer_parcial'] ?? null;

    // Insertar materia
    $sqlMateria = "INSERT INTO materia (nombre_materia, id_usuario) VALUES (:nombre_materia, :id_usuario)";
    $stmtMateria = $conn->prepare($sqlMateria);

    if (!$stmtMateria) {
        throw new Exception("Error en la consulta de materia");
    }

    // Usar bindValue para asignar valores a los parámetros
    $stmtMateria->bindValue(':nombre_materia', $nombre_materia, PDO::PARAM_STR);
    $stmtMateria->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if (!$stmtMateria->execute()) {
        throw new Exception("Error al insertar materia");
    }

    $id_materia = $conn->lastInsertId();

    // Insertar calificaciones
    $sqlCalificaciones = "INSERT INTO calificaciones (id_materia, primer_parcial, segundo_parcial, tercer_parcial) VALUES (:id_materia, :primer_parcial, :segundo_parcial, :tercer_parcial)";
    $stmtCalif = $conn->prepare($sqlCalificaciones);

    if (!$stmtCalif) {
        throw new Exception("Error en la consulta de calificaciones");
    }

    // Usar bindValue para asignar valores a los parámetros
    $stmtCalif->bindValue(':id_materia', $id_materia, PDO::PARAM_INT);
    $stmtCalif->bindValue(':primer_parcial', $primer_parcial, PDO::PARAM_INT);
    $stmtCalif->bindValue(':segundo_parcial', $segundo_parcial, PDO::PARAM_INT);
    $stmtCalif->bindValue(':tercer_parcial', $tercer_parcial, PDO::PARAM_INT);

    if (!$stmtCalif->execute()) {
        throw new Exception("Error al insertar calificaciones");
    }

    // Enviar respuesta JSON
    echo json_encode(["success" => true, "message" => "Materia y calificaciones guardadas"]);

} catch (Exception $e) {
    // Enviar respuesta JSON en caso de error
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>