<?php 
$pageTitle = "Gestión de Usuarios - Curzilla";
// Asegurarse de que el usuario esté autenticado y sea un administrador
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    // Redirigir a una página de acceso no autorizado o a la página de inicio
    header('Location: /portal_cursos/index.php');
    exit;
}

// Incluir la configuración de la base de datos y obtener los usuarios
$users = [];
try {
    // La ruta al archivo de configuración de la base de datos
    require_once __DIR__ . '/../../config/database.php';
    $pdo = Database::getInstance();

    // Consulta para obtener usuarios y sus roles
    $stmt = $pdo->query("
        SELECT 
            u.id_usuario, 
            u.nombre_completo, 
            u.correo, 
            r.nombre as rol_nombre
        FROM usuarios u
        JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
        JOIN roles r ON ur.id_rol = r.id_rol
        ORDER BY u.id_usuario ASC
    ");
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    // En un entorno de producción, sería mejor registrar este error que mostrarlo
    echo "Error al cargar los usuarios: " . $e->getMessage();
}

include __DIR__ . '/../layouts/layout.php';
?>

<!-- Main Content for User Management -->
<main>
    <header class="gc-header">
        <div class="gc-header-content">
            <h1>Gestión de Usuarios</h1>
        </div>
    </header>
    <!-- Main Section -->
    <section class="explore-section">
        <!-- Users Table -->
        <div class="gc-container">
            <!-- Action Buttons -->
            <div class="admin-actions" role="toolbar">
                <a href="/portal_cursos/views/auth/registro.php" class="btn btn-primary">Agregar Nuevo Usuario</a>
            </div>
            <div class="gc-table-container">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo Electrónico</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No se encontraron usuarios.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($user['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($user['correo']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($user['rol_nombre'])); ?></td>
                                <td class="action-buttons">
                                    <a href="#" class="btn btn-secondary btn-sm">Editar</a>
                                    <a href="#" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
