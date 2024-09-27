<?php
// Función para cifrar datos (por ejemplo, información sensible) utilizando SHA-256 (no reversible)
function hashData($data) {
    return hash('sha256', $data);  // Genera un resumen criptográfico utilizando SHA-256
}

// Función para verificar si los datos coinciden con el hash almacenado
function verifyData($data, $hashed_data) {
    return hash('sha256', $data) === $hashed_data;  // Compara los datos hashados
}

// Función para cifrar una contraseña utilizando SHA-256 (no reversible)
function hashPassword($password) {
    return hash('sha256', $password);  // Usa SHA-256 para hash de contraseñas
}

// Función para verificar si la contraseña ingresada coincide con el hash
function verifyPassword($password, $hashed_password) {
    return hash('sha256', $password) === $hashed_password;  // Compara los hashes de las contraseñas
}
?>
