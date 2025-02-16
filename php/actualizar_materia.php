<?php
include 'conexion.php';

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    // Leer datos del cuerpo de la solicitud (incluso para PUT)
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['id_materia'], $data['nombre_materia'])) {
        throw new Exception("Datos incompletos");
    }

    // Obtener datos
    $id_materia = $data['id_materia'];
    $nombre_materia = $data['nombre_materia'];
    $primer_parcial = $data['primer_parcial'] ?? null;
    $segundo_parcial = $data['segundo_parcial'] ?? null;
    $tercer_parcial = $data['tercer_parcial'] ?? null;

    // Actualizar materia
    $sqlMateria = "UPDATE materia SET nombre_materia = :nombre_materia WHERE id_materia = :id_materia";
    $stmtMateria = $conn->prepare($sqlMateria);

    if (!$stmtMateria) {
        throw new Exception("Error en la consulta de materia");
    }

    $stmtMateria->bindValue(':nombre_materia', $nombre_materia, PDO::PARAM_STR);
    $stmtMateria->bindValue(':id_materia', $id_materia, PDO::PARAM_INT);

    if (!$stmtMateria->execute()) {
        throw new Exception("Error al actualizar materia");
    }

    // Actualizar calificaciones
    $sqlCalificaciones = "UPDATE calificaciones SET primer_parcial = :primer_parcial, segundo_parcial = :segundo_parcial, tercer_parcial = :tercer_parcial WHERE id_materia = :id_materia";
    $stmtCalif = $conn->prepare($sqlCalificaciones);

    if (!$stmtCalif) {
        throw new Exception("Error en la consulta de calificaciones");
    }

    $stmtCalif->bindValue(':primer_parcial', $primer_parcial, PDO::PARAM_INT);
    $stmtCalif->bindValue(':segundo_parcial', $segundo_parcial, PDO::PARAM_INT);
    $stmtCalif->bindValue(':tercer_parcial', $tercer_parcial, PDO::PARAM_INT);
    $stmtCalif->bindValue(':id_materia', $id_materia, PDO::PARAM_INT);

    if (!$stmtCalif->execute()) {
        throw new Exception("Error al actualizar calificaciones");
    }

    // Enviar respuesta JSON
    echo json_encode(["success" => true, "message" => "Materia y calificaciones actualizadas"]);

} catch (Exception $e) {
    // Enviar respuesta JSON en caso de error
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>