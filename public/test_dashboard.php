<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("<h1>Error: Inicia sesión primero</h1>");
}

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Test Dashboard</title>";
echo "<style>body{font-family:Arial;margin:20px;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#4CAF50;color:white;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";
echo "</head><body>";

echo "<h1>Diagnóstico Dashboard Instructor</h1>";

$instructor_id = $_SESSION['user_id'];
echo "<div class='success'><strong>Usuario autenticado:</strong> ID $instructor_id - " . $_SESSION['user_nombre'] . " (" . $_SESSION['user_rol'] . ")</div>";

require_once __DIR__ . '/../config/database.php';

// Test 1: Consulta directa
echo "<h2>1. Consulta Directa a la Base de Datos</h2>";
try {
    $stmt = $pdo->prepare("SELECT id_curso, titulo, estado FROM cursos WHERE id_instructor = ?");
    $stmt->execute([$instructor_id]);
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p><strong>Cursos encontrados:</strong> " . count($cursos) . "</p>";
    
    if (count($cursos) > 0) {
        echo "<table><tr><th>ID</th><th>Título</th><th>Estado</th></tr>";
        foreach ($cursos as $curso) {
            echo "<tr><td>{$curso['id_curso']}</td><td>{$curso['titulo']}</td><td>{$curso['estado']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>No se encontraron cursos</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

// Test 2: Modelo Instructor
echo "<h2>2. Prueba con InstructorModel</h2>";
try {
    require_once __DIR__ . '/../models/InstructorModel.php';
    $instructorModel = new Instructor($pdo);
    
    $cursos_modelo = $instructorModel->getCursosInstructor($instructor_id);
    echo "<p><strong>Cursos desde modelo:</strong> " . count($cursos_modelo) . "</p>";
    
    if (count($cursos_modelo) > 0) {
        echo "<details><summary>Ver detalles</summary><pre>" . print_r($cursos_modelo, true) . "</pre></details>";
    }
    
    $stats = $instructorModel->getStatsInstructor($instructor_id);
    echo "<p><strong>Estadísticas:</strong></p><pre>" . print_r($stats, true) . "</pre>";
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
}

// Test 3: Controlador
echo "<h2>3. Prueba de Controlador</h2>";
try {
    require_once __DIR__ . '/../controllers/InstructorController.php';
    new InstructorController($pdo);
    echo "<p class='success'>✓ Controlador instanciado correctamente</p>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Error: " . $e->getMessage() . "</p>";
}

echo "<hr><p><strong><a href='instructor_router.php'>→ Ir al Dashboard Real</a></strong></p>";
echo "</body></html>";
?>