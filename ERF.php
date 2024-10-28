<?php
require 'RedirFunction.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page'])) {
    $page = $_POST['page'];
    echo "Valor de page recibido: " . htmlspecialchars($page) . "<br>";
    
    // Llamada a la función de redirección
    redireccionarPagina($page);
}
?>
