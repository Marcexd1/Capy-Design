<?php
// Llave secreta para cifrar y descifrar (guarda esta clave en un lugar seguro)
define('SECRET_KEY', 'your_secret_key_here'); // Cambia esta clave a una segura
define('ENCRYPTION_METHOD', 'AES-256-CBC');

// Función para cifrar datos (por ejemplo, contraseñas o información sensible)
function encryptData($data) {
    $iv_length = openssl_cipher_iv_length(ENCRYPTION_METHOD); // Longitud del IV
    $iv = openssl_random_pseudo_bytes($iv_length); // Generar IV aleatorio
    $encrypted_data = openssl_encrypt($data, ENCRYPTION_METHOD, SECRET_KEY, 0, $iv);
    
    // Devuelve los datos cifrados junto con el IV, codificados en base64
    return base64_encode($encrypted_data . '::' . $iv);
}

// Función para descifrar datos cifrados
function decryptData($encrypted_data) {
    // Dividir los datos cifrados y el IV
    list($encrypted_data, $iv) = explode('::', base64_decode($encrypted_data), 2);
    return openssl_decrypt($encrypted_data, ENCRYPTION_METHOD, SECRET_KEY, 0, $iv);
}

// Función para cifrar una contraseña utilizando password_hash (no reversible)
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Función para verificar si la contraseña ingresada coincide con el hash
function verifyPassword($password, $hashed_password) {
    return password_verify($password, $hashed_password);
}
?>
