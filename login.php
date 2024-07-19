<?php
session_start();
include 'config.php'; // Asegúrate de incluir el archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['re4'];
    $password = $_POST['p4ss'];
    
    // Verificar las credenciales del usuario
    $query = "SELECT * FROM entrenadores WHERE nombre = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Iniciar la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Si se seleccionó "Recuérdame", establecer una cookie
        if (isset($_POST['remember_me'])) {
            setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); // 86400 = 1 día
            setcookie('username', $user['username'], time() + (86400 * 30), "/");
        }

        header("Location: Menu.html"); // Redirigir a la página de bienvenida
    } else {
        echo "Credenciales incorrectas";
    }
}
?>
