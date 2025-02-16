<?php
require_once "conexion.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id_usuario"])) {
    $id_usuario = $_GET["id_usuario"];

    try {
        $stmt = $conn->prepare("
            SELECT m.id_materia, m.nombre_materia, 
                   c.primer_parcial, c.segundo_parcial, c.tercer_parcial, c.estado
            FROM materia m
            LEFT JOIN calificaciones c ON m.id_materia = c.id_materia
            WHERE m.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($resultados);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener calificaciones: " . $e->getMessage()]);
    }
}
?>
