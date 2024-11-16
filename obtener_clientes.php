<?php
require_once 'config.php';

$order = "id";
if (isset($_GET['order'])) {
    if ($_GET['order'] == "nombre_asc") {
        $order = "nombre ASC";
    } elseif ($_GET['order'] == "id_m_asc") {
        $order = "id_m ASC";
    }
}

$stmt = $conn->prepare("SELECT id, nombre, CI, Peso, direccion, id_m FROM clientes ORDER BY $order");
$stmt->execute();
$result = $stmt->get_result();

$clientes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

$stmt->close();
$conn->close();

echo json_encode($clientes);
?>
