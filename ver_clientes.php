<?php
// Conecta
$conn = new mysqli("localhost", "usuario", "contraseña", "base_de_datos");

// Verifica
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta
$sql = "SELECT id, nombre, email FROM clientes";
$result = $conn->query($sql);

echo "<h3>Clientes</h3>";
echo "<table>";
echo "<tr><th>ID</th><th>Nombre</th><th>Email</th></tr>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["email"] . "</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>No se encontraron clientes</td></tr>";
}
echo "</table>";

// Cierrar la conexión
$conn->close();
?>
