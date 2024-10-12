<?php
// Iniciar sesión

// Función para cargar el contenido de la página
function cargarContenido() {
    // Verificar la sesión
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.html");
        exit();
    }

    // Cargar la página basada en el parámetro 'page'
    if (isset($_GET['page'])) {
        $page = $_GET['page'];

        // Incluir la página correspondiente
        switch ($page) {
            case 'perfil':
                include 'perfil.php';
                break;
            case 'ver_clientes':
                include 'ver_clientes.php';
                break;
            case 'agregar_clases':
                include 'agregar_clases.php';
                break;
                case 'error':
                    include 'error';
                    echo "<p>Pagina no encontrada</p>";
                    break;
            // Agrega más páginas aquí según sea necesario
        }
    } else {
        echo "<p>Bienvenido, seleccione una opción del menú.</p>";
    }
}
?>