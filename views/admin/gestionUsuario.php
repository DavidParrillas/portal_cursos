<?php 
$pageTitle = "Gestión de Usuarios - Curzilla";
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
                    <caption>Lista de usuarios registrados en el sistema</caption>
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
</main>
