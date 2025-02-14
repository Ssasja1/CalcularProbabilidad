<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido_paterno = $_POST["apellido_paterno"];
    $apellido_materno = $_POST["apellido_materno"];
    $matricula = $_POST["matricula"];
    $cuatrimestre = $_POST["cuatrimestre"];
    $carrera = $_POST["carrera"];

    $sql = "INSERT INTO usuario (nombre, apellido_paterno, apellido_materno, matricula, cuatrimestre, carrera) 
            VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$matricula', '$cuatrimestre', '$carrera')";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario agregado con éxito";
    } else {
        echo "Error al agregar usuario: " . $conn->error;
    }

    $conn->close();
}
?>
