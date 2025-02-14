<?php
require_once "conexion.php";

// Función para agregar un nuevo usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar_usuario"])) {
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST["apellido_paterno"];
    $apellido_materno = $_POST["apellido_materno"];
    $matricula = $_POST["matricula"];
    $cuatrimestre = $_POST["cuatrimestre"];
    $carrera = $_POST["carrera"];

    try {
        $stmt = $conn->prepare("INSERT INTO usuario (nombre, apellido_paterno, apellido_materno, matricula, cuatrimestre, carrera) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido_paterno, $apellido_materno, $matricula, $cuatrimestre, $carrera]);

        echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al registrar usuario: " . $e->getMessage()]);
    }
}

// Función para obtener todos los usuarios
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["listar_usuarios"])) {
    try {
        $stmt = $conn->prepare("SELECT id_usuario, matricula, nombre, apellido_paterno, apellido_materno, cuatrimestre, carrera FROM usuario");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener usuarios: " . $e->getMessage()]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["listar_materias"])) {
    $idUsuario = $_GET["id_usuario"];

    try {
        // Obtener las materias del usuario
        $stmtMaterias = $conn->prepare("SELECT * FROM materia WHERE id_usuario = ?");
        $stmtMaterias->execute([$idUsuario]);
        $materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

        // Obtener las calificaciones de cada materia
        foreach ($materias as &$materia) {
            $stmtCalificaciones = $conn->prepare("SELECT * FROM calificaciones WHERE id_materia = ?");
            $stmtCalificaciones->execute([$materia["id_materia"]]);
            $calificaciones = $stmtCalificaciones->fetch(PDO::FETCH_ASSOC);

            if ($calificaciones) {
                $materia["primer_parcial"] = $calificaciones["primer_parcial"];
                $materia["segundo_parcial"] = $calificaciones["segundo_parcial"];
                $materia["tercer_parcial"] = $calificaciones["tercer_parcial"];
            }
        }

        echo json_encode($materias);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener materias y calificaciones: " . $e->getMessage()]);
    }
}
?>
