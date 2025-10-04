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
$roles = [];
try {
    require_once __DIR__ . '/../../config/database.php';
    $pdo = Database::getInstance();

    // Consulta para obtener usuarios y sus roles
    $stmt = $pdo->query("
        SELECT 
            u.id_usuario, 
            u.nombre_completo, 
            u.correo, 
            r.id_rol,
            r.nombre as rol_nombre
        FROM usuarios u
        JOIN usuarios_roles ur ON u.id_usuario = ur.id_usuario
        JOIN roles r ON ur.id_rol = r.id_rol
        ORDER BY u.id_usuario ASC
    ");
    $users = $stmt->fetchAll();

    // Obtener todos los roles disponibles para el selector
    $stmtRoles = $pdo->query("SELECT id_rol, nombre FROM roles ORDER BY nombre ASC");
    $roles = $stmtRoles->fetchAll();
} catch (Exception $e) {
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
                <button type="button" class="btn btn-primary" onclick="abrirModalUsuario('crear')">Agregar Nuevo Usuario</button>
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
                                    <button class="btn btn-secondary btn-sm" 
                                            onclick="abrirModalUsuario('editar', <?php echo $user['id_usuario']; ?>)">
                                        <i class="fas fa-edit"></i> Editar
                                    </button>
                                    <button class="btn btn-danger btn-sm" 
                                            onclick="confirmarEliminar(<?php echo $user['id_usuario']; ?>, '<?php echo htmlspecialchars(addslashes($user['nombre_completo'])); ?>')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
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

<!-- Modal para Crear/Editar Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioLabel">Crear Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            
            <form id="formUsuario" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" id="userId">
                    <input type="hidden" name="accion" id="accionForm">
                    
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                        <div class="invalid-feedback">Por favor ingrese el nombre completo.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                        <div class="invalid-feedback">Por favor ingrese un correo válido.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_rol" class="form-label">Rol <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_rol" name="id_rol" required>
                            <option value="">Seleccione un rol...</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo $rol['id_rol']; ?>">
                                    <?php echo htmlspecialchars(ucfirst($rol['nombre'])); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Por favor seleccione un rol.</div>
                    </div>
                    
                    <div class="mb-3" id="divContrasena">
                        <label for="contrasena" class="form-label">
                            Contraseña <span class="text-danger" id="asteriscoPassword">*</span>
                        </label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena">
                        <small class="text-muted" id="helpPassword">
                            La contraseña debe tener al menos 8 caracteres.
                        </small>
                        <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">
                        <i class="fas fa-save"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script específico para la gestión de usuarios -->
<script src="/portal_cursos/public/assets/js/gestion-usuarios.js?v=<?php echo time(); ?>"></script>