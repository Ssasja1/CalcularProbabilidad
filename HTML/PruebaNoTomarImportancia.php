<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones</title>
    <link rel="stylesheet" href="../css/EstilosProbabilidad.css">
</head>
<body>
    <div class="container">
        <h2>Calificaciones</h2>

        <table>
            <thead>
                <tr>
                    <th>Asignatura</th>
                    <th>Primer Parcial</th>
                    <th>Segundo Parcial</th>
                    <th>Tercer Parcial</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../php/conexion.php'; // Asegúrate de incluir tu conexión a la BD
                
                $id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0; // Verifica que el id_usuario se reciba correctamente
                
                if ($id_usuario > 0) { // Solo ejecuta la consulta si hay un ID válido
                    $sql = "SELECT m.nombre_materia, c.primer_parcial, c.segundo_parcial, c.tercer_parcial 
                            FROM calificaciones c 
                            JOIN materia m ON c.id_materia = m.id_materia 
                            WHERE c.id_usuario = $id_usuario";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $promedio = ($row["primer_parcial"] + $row["segundo_parcial"] + $row["tercer_parcial"]) / 3;
                            $estado = $promedio >= 60 ? "Aprobado" : "Reprobado";
                            $colorEstado = ($estado === "Aprobado") ? "green" : "red";

                            echo "<tr>
                                    <td>{$row['nombre_materia']}</td>
                                    <td><input type='text' value='{$row['primer_parcial']}'></td>
                                    <td><input type='text' value='{$row['segundo_parcial']}'></td>
                                    <td><input type='text' value='{$row['tercer_parcial']}'></td>
                                    <td class='estado' style='color: $colorEstado;'>$estado</td>
                                    <td>
                                        <button class='boton-actualizar'>
                                            <img src='../img/actualizar.png' alt='Actualizar' width='15'> Actualizar
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay registros</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='color: red;'>Error: ID de usuario no válido</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <button class="boton-agregar">+ Agregar Materia</button>
        <p id="probabilidad">Probabilidad de aprobación: 0%</p>
    </div>
</body>
</html>
