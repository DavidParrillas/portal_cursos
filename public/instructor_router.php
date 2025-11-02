<?php
/**
 * Router específico para las rutas del instructor
 * Este archivo maneja todas las rutas relacionadas con el módulo de instructor
 */

session_start();

error_log("[DEBUG] Session at router start: " . json_encode($_SESSION));
error_log("[DEBUG] Session ID: " . session_id());

// Incluir la clase Database
require_once __DIR__ . '/../config/database.php';

// Obtener la instancia de conexión PDO usando el patrón Singleton
try {
    $pdo = Database::getInstance();
    error_log("[DEBUG] Database connection successful");
} catch (Exception $e) {
    error_log("[DEBUG] Database connection failed: " . $e->getMessage());
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Incluir el BaseModel (necesario para que Instructor funcione)
require_once __DIR__ . '/../models/BaseModel.php';
error_log("[DEBUG] BaseModel.php included");

// Incluir el InstructorModel
require_once __DIR__ . '/../models/InstructorModel.php';
error_log("[DEBUG] InstructorModel.php included");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    require_once __DIR__ . '/../controllers/CursoController.php';
    exit();
}

require_once __DIR__ . '/../controllers/InstructorController.php';
error_log("[DEBUG] InstructorController.php included successfully");

error_log("[DEBUG] user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("[DEBUG] user_rol: " . ($_SESSION['user_rol'] ?? 'NOT SET'));

error_log("[DEBUG] About to create InstructorController instance");
if (!class_exists('InstructorController')) {
    error_log("[DEBUG] ERROR: InstructorController class does not exist!");
    die("InstructorController class not found");
}
$instructorController = new InstructorController($pdo);
error_log("[DEBUG] InstructorController instance created successfully");

// Obtener la acción desde la URL
$action = $_GET['action'] ?? 'dashboard';
error_log("[DEBUG] Action: " . $action);

// Enrutar según la acción
switch ($action) {
    case 'dashboard':
        $instructorController->dashboard();
        break;

    case 'crearCurso':
        include __DIR__ . '/../views/courses/crearCurso.php';
        break;
        
    default:
        // Cualquier otra acción redirige al dashboard
        header('Location: /portal_cursos/public/instructor_router.php?action=dashboard');
        exit();
}
?>
