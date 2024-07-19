<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];

    // Consulta para insertar un nuevo entrenador
    $sql = "INSERT INTO entrenadores (nombre) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        echo "Registro exitoso!";
        header('Location: index.html');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
