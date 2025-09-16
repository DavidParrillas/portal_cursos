<?php
// Manejo de sesiones
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
