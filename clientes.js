// usuarios.js

// Función para cargar usuarios con el orden especificado
function cargarClientes(order = 'id') {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "obtener_clientes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Enviar el parámetro de orden
    xhr.send("order=" + order);

    // Procesar la respuesta cuando esté lista
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("clientes-table-body").innerHTML = xhr.responseText;
        }
    };
}

// Cargar usuarios por defecto al cargar la página
window.onload = function () {
    cargarUsuarios();
};
