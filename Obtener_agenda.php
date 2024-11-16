<?php
include 'config.php';

$sql = "SELECT * FROM agenda";
$result = $conn->query($sql);

$agendas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendas[] = $row;
    }
}

$conn->close();

echo json_encode($agendas);
?>
