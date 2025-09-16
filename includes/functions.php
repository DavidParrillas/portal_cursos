<?php
// Funciones globales del sistema

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Agregar más funciones globales según sea necesario
