<?php
require_once 'config.php';

$sql = "SELECT id_m, Status, F_Start, F_Expired, F_Creacion FROM membresias ORDER BY id_m";
$result = $conn->query($sql);

$membresias = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $membresias[] = $row;
    }
}

$conn->close();

echo json_encode($membresias);
?>
