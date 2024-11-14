<?php
// obtener_usuarios.php

// Conectar a la base de datos
require_once 'config.php';

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Definir el orden predeterminado
$order = "id";
if (isset($_POST['order'])) {
    if ($_POST['order'] == "nombre_asc") {
        $order = "nombre ASC";
    } elseif ($_POST['order'] == "id_m_asc") {
        $order = "id_m ASC";
    }
}

// Consultar la tabla de usuarios
$sql = "SELECT id, nombre, CI, Peso, direccion, id_m FROM clientes ORDER BY $order";
$result = $conn->query($sql);

// Crear una tabla HTML con los resultados
$usuarios_html = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios_html .= "<tr>";
        $usuarios_html .= "<td>" . $row["id"] . "</td>";
        $usuarios_html .= "<td>" . $row["nombre"] . "</td>";
        $usuarios_html .= "<td>" . $row["CI"] . "</td>";
        $usuarios_html .= "<td>" . $row["Peso"] . "</td>";
        $usuarios_html .= "<td>" . $row["direccion"] . "</td>";
        $usuarios_html .= "<td>" . $row["id_m"] . "</td>";
        $usuarios_html .= "</tr>";
    }
} else {
    $usuarios_html = "<tr><td colspan='6'>No hay usuarios</td></tr>";
}

// Cerrar conexión
$conn->close();

// Devolver el HTML
echo $usuarios_html;
?>
