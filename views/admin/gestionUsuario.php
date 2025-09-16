<?php 
$pageTitle = "Registro - Curzilla";
include __DIR__ . '/../layouts/layout.php';
?>

<!-- Main Content for User Management -->
<main>
    <div class="gc-header">
        <div class="gc-header-content">
            <h1>Gestión de Usuarios</h1>
        </div>
    </div>
        <!-- Main Section -->
        <section class="explore-section">
            <!-- Users Table -->
            <div class="gc-container">
                <!-- Action Buttons -->
            <div class="admin-actions">
                <a href="/portal_cursos/views/auth/registro.php" class="btn btn-primary">Agregar Nuevo Usuario</a>
            </div>
                <div class="gc-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo Electrónico</th>
                                <th>Rol</th>
                                <th>Acciones</th>
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
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($user['correo']); ?></td>
                                    <td><?php echo htmlspecialchars(ucfirst($user['rol'])); ?></td>
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
    </div>
</main>
