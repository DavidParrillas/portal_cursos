<?php
/**
 * Router específico para las rutas del instructor
 * Este archivo maneja todas las rutas relacionadas con el módulo de instructor
 */

session_start();

// ========== SOLO PARA PRUEBAS - BORRAR DESPUÉS ==========
$_SESSION['user_id'] = 1;
$_SESSION['user_rol'] = 'instructor';
$_SESSION['user_nombre'] = 'Juan Instructor';  // ← AGREGADO
$_SESSION['user_email'] = 'instructor@test.com';  // ← AGREGADO
// =========================================================

// Incluir la clase Database
require_once __DIR__ . '/../config/database.php';

// Obtener la instancia de conexión PDO usando el patrón Singleton
try {
    $pdo = Database::getInstance();
} catch (Exception $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Incluir el BaseModel (necesario para que Instructor funcione)
require_once __DIR__ . '/../models/BaseModel.php';

// Incluir el controlador
require_once __DIR__ . '/../controllers/InstructorController.php';

// Obtener la acción desde la URL
$action = $_GET['action'] ?? 'dashboard';

// Crear instancia del controlador
$instructorController = new InstructorController($pdo);

// Enrutar según la acción
switch ($action) {
    case 'dashboard':
        // Este es el único método que existe en tu InstructorController
        $instructorController->dashboard();
        break;
        
    default:
        // Cualquier otra acción redirige al dashboard
        header('Location: /portal_cursos/public/instructor_router.php?action=dashboard');
        exit();
}
?>