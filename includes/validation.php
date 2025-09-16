<?php
// Funciones de validación de datos

function is_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_required($value) {
    return !empty(trim($value));
}
// Agregar más validaciones según sea necesario
