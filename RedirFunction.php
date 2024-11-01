<?php
function redireccionarPagina($page) {
    // Array de rutas de redirección
    $rutas = [
        'ver_membresias' => 'Menu_Ver_Membrecias.html',
        'ver_clientes' => 'Menu_Ver_Clientes.html',
        'ver_agenda' => 'Menu_Ver_Agenda.html',
        'ver_horario_dia' => 'Menu_Horarios.html',
        'agregar_clases' => 'Menu_Agregar_Agenda.html',
        'modificar_clases' => 'Menu_Modificar_Agenda.html',
        'agregar_clientes' => 'Menu_Agregar_Clientes.html',
        'actualizar_estados' => 'Menu_Actualizar_Estados.html',
        'perfil' => 'Menu_Perfil.html'
    ];

    // Si el caso existe en el array, redirige; si no, ejecuta otra acción
    if (array_key_exists($page, $rutas)) {
        header("Location: " . $rutas[$page]);
        exit;
    } elseif ($page === 'configuracion') {
        echo "<p>Se está planteando esta sección.</p>";
    }
}