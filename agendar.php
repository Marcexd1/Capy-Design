<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // Agregar una nueva entrada a la agenda
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $fecha_agenda = $_POST['fecha_agenda'];
        $estado = 0;  // Estado inicial: 0 (pendiente)

        $stmt = $conn->prepare("INSERT INTO agenda (Nombre, Descripción, fecha_agenda, estado, fecha_creacion) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssi", $nombre, $descripcion, $fecha_agenda, $estado);

        if ($stmt->execute() === TRUE) {
            header("Location: Menu_Agregar_Agenda.html?success=1");
        } else {
            header("Location: Menu_Agregar_Agenda.html?error=1");
        }

        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Actualizar el estado de una entrada existente
        $id_a = $_POST['id_a'];
        $estado = $_POST['estado'];

        $stmt = $conn->prepare("UPDATE agenda SET estado = ?, fecha_estado = NOW() WHERE id_a = ?");
        $stmt->bind_param("ii", $estado, $id_a);

        if ($stmt->execute() === TRUE) {
            header("Location: Menu_Ver_Agenda.html?success=1");
        } else {
            header("Location: Menu_Ver_Agenda.html?error=1");
        }

        $stmt->close();
    }
    $conn->close();
}
?>
