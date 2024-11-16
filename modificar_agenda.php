<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_a = $_POST['id_a'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fecha_agenda = $_POST['fecha_agenda'];
    $estado = $_POST['estado'];

    $stmt = $conn->prepare("UPDATE agenda SET Nombre = ?, Descripción = ?, fecha_agenda = ?, estado = ? WHERE id_a = ?");
    $stmt->bind_param("sssii", $nombre, $descripcion, $fecha_agenda, $estado, $id_a);

    if ($stmt->execute() === TRUE) {
        header("Location: Menu_Modificar_Agenda.html?success=1");
    } else {
        header("Location: Menu_Modificar_Agenda.html?error=1");
    }

    $stmt->close();
    $conn->close();
}
?>
