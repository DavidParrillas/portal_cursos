<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log("ROUTER INSTRUCTOR - User ID: " . ($_SESSION['user_id'] ?? 'no definido'));

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/InstructorController.php';

if (!isset($pdo)) {
    die("Error: No hay conexión a la base de datos");
}

$controller = new InstructorController($pdo);
$action = $_GET['action'] ?? 'dashboard';

if ($action === 'crearCurso') {
    // Redirige a la vista de creación de curso
    include __DIR__ . '/../views/courses/crearCurso.php';
    exit;
}

if ($action === 'eliminarCurso') {
    // Es una llamada API, el controlador se encarga de la respuesta.
    $controller->eliminarCurso();
    exit;
}

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    $controller->dashboard();
}
?>