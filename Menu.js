// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar el ícono de usuario y el menú
    const userIcon = document.getElementById('userIcon');
    const userMenu = document.getElementById('userMenu');

    // Función para alternar la visibilidad del menú de usuario
    userIcon.addEventListener('click', function() {
        userMenu.classList.toggle('show'); // Mostrar u ocultar el menú
    });

    // Cerrar el menú si se hace clic fuera de él
    window.addEventListener('click', function(e) {
        if (!userIcon.contains(e.target) && !userMenu.contains(e.target)) {
            userMenu.classList.remove('show'); // Ocultar el menú
        }
    });
});


