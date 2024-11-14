<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "usuario", "contraseña", "base_de_datos");

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener las membresías
$sql = "SELECT id, nombre, precio FROM membresias";
$result = $conn->query($sql);

echo "<h3>Membresías</h3>";
echo "<table>";
echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th></tr>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["precio"] . "</td></tr>";
    }
} else {
    echo "<tr><td colspan='3'>No se encontraron membresías</td></tr>";
}
echo "</table>";

// Cerrar la conexión
$conn->close();
?>
