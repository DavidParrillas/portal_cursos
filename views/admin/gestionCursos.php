<?php 
$pageTitle = "Gestión de Cursos - Curzilla";

// Asegurarse de que el usuario esté autenticado y sea un administrador
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: /portal_cursos/index.php');
    exit;
}

// Incluir dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Curso.php';

$pdo = Database::getInstance();
$cursoModel = new Curso($pdo);

// Obtener filtros
$filtroEstado = isset($_GET['estado']) ? $_GET['estado'] : '';
$filtroBusqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Construir filtros
$filtros = [];
if (!empty($filtroEstado)) {
    $filtros['estado'] = $filtroEstado;
}
if (!empty($filtroBusqueda)) {
    $filtros['busqueda'] = $filtroBusqueda;
}

// Obtener todos los cursos
$cursos = $cursoModel->obtenerTodosParaAdmin($filtros);

include __DIR__ . '/../layouts/layout.php';
?>

<main>
    <header class="gc-header">
        <div class="gc-header-content">
            <h1>Gestión de cursos</h1>
            <p class="gc-subtitle">Administra y revisa todos los cursos de la plataforma</p>
        </div>
    </header>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['mensaje_tipo'] ?? 'info' ?>">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['mensaje_tipo']); ?>
    <?php endif; ?>

    <section class="ct-container">
        <div class="ct-table-wrapper">
            <?php if (empty($cursos)): ?>
                <div class="gc-empty-state" style="padding: 40px; text-align: center;">
                    <i class="fa-solid fa-inbox fa-3x" style="color: #d1d5db;"></i>
                    <p style="margin-top: 16px; font-size: 1.1rem; color: #6b7280;">No se encontraron cursos con los filtros seleccionados</p>
                </div>
            <?php else: ?>
                <table class="ct-table">
                    <thead>
                        <tr>
                            <th scope="col">Nombre del curso</th>
                            <th scope="col">Instructor</th>
                            <th scope="col">Inscritos</th>
                            <th scope="col">Calificación</th>
                            <th scope="col">Categorías</th>
                            <th scope="col">Fecha de solicitud</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-center">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $curso): ?>
                            <tr>
                                <td>
                                    <div class="ct-course-info">
                                        <?php if (!empty($curso['portada'])): ?>
                                            <img src="<?= htmlspecialchars($curso['portada']) ?>" 
                                                alt="<?= htmlspecialchars($curso['titulo']) ?>"
                                                class="ct-course-thumbnail">
                                        <?php endif; ?>
                                        <div class="ct-course-details">
                                            <strong><?= htmlspecialchars($curso['titulo']) ?></strong>
                                            <small>$<?= number_format($curso['precio'], 2) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ct-instructor-info">
                                        <strong><?= htmlspecialchars($curso['instructor_nombre']) ?></strong>
                                        <small><?= htmlspecialchars($curso['instructor_correo']) ?></small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="ct-badge ct-badge-enrolled"><?= $curso['total_inscritos'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="ct-badge ct-badge-rating">
                                        <i class="fa-solid fa-star"></i>
                                        <?= number_format($curso['promedio_calificacion'] ?? 0, 1) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $categorias = explode(', ', $curso['categorias'] ?? 'Sin categoría');
                                    foreach ($categorias as $cat): ?>
                                        <span class="ct-badge ct-badge-category"><?= htmlspecialchars($cat) ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($curso['creado_en'])) ?></td>
                                <td>
                                    <span class="ct-badge ct-badge-status ct-badge-<?= strtolower($curso['estado']) ?>">
                                        <?= ucfirst(strtolower($curso['estado'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="ct-actions">
                                        <a href="ver_curso.php?id=<?= $curso['id_curso'] ?>" class="ct-action-btn" title="Ver detalles">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <?php if ($curso['estado'] === 'PENDIENTE'): ?>
                                            <button onclick="aprobarCurso(<?= $curso['id_curso'] ?>)" class="ct-action-btn approve" title="Aprobar">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                            <button onclick="rechazarCurso(<?= $curso['id_curso'] ?>)" class="ct-action-btn reject" title="Rechazar">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($curso['estado'] === 'PUBLICADO'): ?>
                                            <button onclick="archivarCurso(<?= $curso['id_curso'] ?>)" class="ct-action-btn archive" title="Archivar">
                                                <i class="fa-solid fa-archive"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($curso['estado'] === 'ARCHIVADO'): ?>
                                            <button onclick="publicarCurso(<?= $curso['id_curso'] ?>)" class="ct-action-btn publish" title="Publicar">
                                                <i class="fa-solid fa-upload"></i>
                                            </button>
                                        <?php endif; ?>

                                        <button onclick="eliminarCurso(<?= $curso['id_curso'] ?>)" class="ct-action-btn delete" title="Eliminar">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Modal de confirmación -->
<div id="modalConfirmacion" class="gc-modal" style="display: none;">
    <div class="gc-modal-content">
        <div class="gc-modal-header">
            <h2 id="modalTitulo">Confirmar acción</h2>
            <button class="gc-modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="gc-modal-body">
            <p id="modalMensaje"></p>
        </div>
        <div class="gc-modal-footer">
            <button class="gc-btn gc-btn-secondary" onclick="cerrarModal()">Cancelar</button>
            <button class="gc-btn gc-btn-primary" id="btnConfirmar">Confirmar</button>
        </div>
    </div>
</div>

<script src="/portal_cursos/assets/js/gestion-cursos.js"></script>

</body>
</html>