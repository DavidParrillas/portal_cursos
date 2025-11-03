<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Editar Curso - Curzilla";
ob_start();

// Asegurarse de que el usuario esté autenticado y sea un instructor
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'instructor') {
    header('Location: /portal_cursos/index.php');
    exit;
}

// Verificar que se recibió el ID del curso
if (!isset($_GET['id'])) {
    header('Location: /portal_cursos/public/instructor_router.php?action=dashboard');
    exit;
}

// Obtener los datos del curso
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Categoria.php';
require_once __DIR__ . '/../../models/Curso.php';
require_once __DIR__ . '/../../models/Material.php';

$pdo = Database::getInstance();
$categoriaModel = new Categoria($pdo);
$cursoModel = new Curso($pdo);
$materialModel = new Material($pdo);

$categorias = $categoriaModel->obtenerTodas();
$curso = $cursoModel->obtenerPorId($_GET['id']);
$materiales = $materialModel->obtenerPorCurso($_GET['id']);
$videos = $materialModel->obtenerPorTipo($_GET['id'], 'video');
$archivos = $materialModel->obtenerPorTipo($_GET['id'], 'archivo');

// Verificar que el curso existe y pertenece al instructor actual
if (!$curso || $curso['id_instructor'] != $_SESSION['user_id']) {
    header('Location: /portal_cursos/public/instructor_router.php?action=dashboard');
    exit;
}

// Parsear la duración para obtener secciones, clases y horas
$duracion_parts = [
    'secciones' => 0,
    'clases' => 0,
    'horas' => 0,
];
if (!empty($curso['duracion'])) {
    preg_match('/(\d+)\s*secci(o|ó)n(es)?/', $curso['duracion'], $matches_secciones);
    preg_match('/(\d+)\s*clase(s)?/', $curso['duracion'], $matches_clases);
    preg_match('/(\d+)\s*hora(s)?/', $curso['duracion'], $matches_horas);
    $duracion_parts['secciones'] = isset($matches_secciones[1]) ? (int)$matches_secciones[1] : 0;
    $duracion_parts['clases'] = isset($matches_clases[1]) ? (int)$matches_clases[1] : 0;
    $duracion_parts['horas'] = isset($matches_horas[1]) ? (int)$matches_horas[1] : 0;
}
?>

<!-- Main Content -->
<main class="curzilla-main-content">
    <section class="curzilla-container">
        <h1 class="curzilla-page-title">Editar Curso</h1>

        <form class="curzilla-course-form" method="POST" action="/portal_cursos/controllers/CursoController.php?action=actualizar" enctype="multipart/form-data" id="form-editar-curso">
            <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso['id_curso']) ?>">
            
            <fieldset>
                <legend class="sr-only">Detalles del Curso</legend>
                <div class="curzilla-form-row">
                    <div class="curzilla-form-group">
                        <label for="course-title">Título del curso</label>
                        <input type="text" id="course-title" name="titulo" class="curzilla-form-input" required value="<?= htmlspecialchars($curso['titulo']) ?>">
                    </div>
                    <div class="curzilla-form-group" id="participants-group">
                        <label for="max-participants">Máximo de participantes</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="max-participants">-</button>
                            <input type="number" id="max-participants" name="cupos" value="<?= htmlspecialchars($curso['cupos']) ?>" min="0" class="curzilla-counter-value" required>
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="max-participants">+</button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="curzilla-form-group">
                    <label for="course-description">Descripción del Curso</label>
                    <textarea id="course-description" name="descripcion" class="curzilla-form-textarea" rows="6" required><?= htmlspecialchars($curso['descripcion']) ?></textarea>
                </div>

                <br>
                <div class="curzilla-form-group">
                    <label for="categorias">Categoría</label>
                    <select id="categorias" name="categorias[]" class="curzilla-form-select" required>
                        <option value="">Seleccionar categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id_categoria'] ?>" <?= ($curso['id_categoria'] == $categoria['id_categoria']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>

            <fieldset class="curzilla-duration-section">
                <legend class="curzilla-section-title">
                    Duración del curso
                </legend>
                <div class="curzilla-duration-controls">
                    <div class="curzilla-form-group">
                        <label for="sections">Secciones</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="sections">-</button>
                            <input type="number" id="sections" name="secciones" value="<?= htmlspecialchars($duracion_parts['secciones']) ?>" min="0" class="curzilla-counter-value curzilla-purple">
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="sections">+</button>
                        </div>
                    </div>
                    
                    <div class="curzilla-form-group">
                        <label for="classes">Clases</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="classes">-</button>
                            <input type="number" id="classes" name="clases" value="<?= htmlspecialchars($duracion_parts['clases']) ?>" min="0" class="curzilla-counter-value curzilla-purple">
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="classes">+</button>
                        </div>
                    </div>
                    
                    <div class="curzilla-form-group">
                        <label for="hours">Horas</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="hours">-</button>
                            <input type="number" id="hours" name="horas" value="<?= htmlspecialchars($duracion_parts['horas']) ?>" min="0" class="curzilla-counter-value curzilla-purple">
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="hours">+</button>
                        </div>
                    </div>
                    
                    <div class="curzilla-form-group">
                        <label for="fecha-inicio">Fecha de Inicio</label>
                        <input type="date" id="fecha-inicio" name="fecha_inicio" class="curzilla-form-input" value="<?= htmlspecialchars($curso['fecha_inicio']) ?>">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend class="sr-only">Modalidad y Precio</legend>
                <div class="curzilla-form-row">
                    <div class="curzilla-form-group">
                        <label for="modality">Modalidad</label>
                        <div class="curzilla-select-container">
                            <select id="modality" name="modalidad" class="curzilla-form-select" required>
                                <option value="">Selecciona</option>
                                <option value="PRESENCIAL" <?= ($curso['modalidad'] == 'PRESENCIAL') ? 'selected' : '' ?>>Presencial</option>
                                <option value="VIRTUAL" <?= ($curso['modalidad'] == 'VIRTUAL') ? 'selected' : '' ?>>Virtual</option>
                                <option value="HIBRIDA" <?= ($curso['modalidad'] == 'HIBRIDA') ? 'selected' : '' ?>>Híbrida</option>
                            </select>
                        </div>
                    </div>
                    <div class="curzilla-form-group">
                        <label for="price">Precio</label>
                        <input type="number" id="price" name="precio" placeholder="0.00" step="0.01" min="0" class="curzilla-form-input curzilla-price-input" required value="<?= htmlspecialchars($curso['precio']) ?>">
                    </div>
                </div>
            </fieldset>
            
            <fieldset>
                <legend class="curzilla-section-title">Videos de YouTube existentes</legend>
                <div id="videos-container" class="curzilla-upload-section">
                    <?php foreach ($videos as $index => $video): ?>
                    <div class="video-item">
                        <div class="curzilla-form-group">
                            <label>Título del video</label>
                            <input type="text" name="videos[<?= $index ?>][titulo]" class="curzilla-form-input" value="<?= htmlspecialchars($video['titulo']) ?>">
                        </div>
                        <div class="curzilla-form-group">
                            <label>URL de YouTube</label>
                            <input type="url" name="videos[<?= $index ?>][url]" class="curzilla-form-input" value="<?= htmlspecialchars($video['ruta_archivo']) ?>">
                        </div>
                        <button type="button" class="curzilla-btn curzilla-btn-danger btn-remove-video">Eliminar video</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="curzilla-btn curzilla-btn-secondary" id="btn-add-video">+ Agregar otro video</button>
            </fieldset>
            
            <fieldset>
                <legend class="curzilla-section-title">Archivos de Apoyo</legend>
                <div class="curzilla-material-list mb-4">
                    <h6 class="curzilla-subsection-title">Archivos existentes</h6>
                    <?php if (empty($archivos)): ?>
                        <p class="text-muted">No hay archivos de apoyo para este curso.</p>
                    <?php else: ?>
                        <ul id="archivos-existentes" class="list-group">
                            <?php foreach ($archivos as $material): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center archivo-existente">
                                <div>
                                    <i class="fa-solid fa-file-alt me-2"></i>
                                    <?= htmlspecialchars($material['titulo']) ?>
                                </div>
                                <button type="button" class="curzilla-btn curzilla-btn-danger btn-sm btn-remove-material" data-id="<?= htmlspecialchars($material['id_material']) ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <input type="hidden" name="materiales_existentes[]" value="<?= htmlspecialchars($material['id_material']) ?>">
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </fieldset>
            
            <fieldset>
                <legend class="curzilla-section-title">Imagen de Portada</legend>
                <div class="curzilla-upload-area">
                    <input type="file" id="portada" name="portada" accept=".png,.jpg,.jpeg" class="curzilla-file-input">
                    <label for="portada" class="curzilla-upload-label">
                        <?php if (!empty($curso['portada'])): ?>
                            <img src="<?= htmlspecialchars($curso['portada']) ?>" alt="Portada actual" class="curzilla-image-preview" id="preview-portada-img" style="max-width: 200px; max-height: 120px; object-fit: cover; border-radius: 8px;">
                        <?php else: ?>
                            <i class="fa-solid fa-image curzilla-upload-icon"></i>
                        <?php endif; ?>
                        <span class="curzilla-upload-text"><strong>Cambiar imagen de portada</strong><br>PNG, JPG (Máx: 2MB)</span>
                    </label>
                </div>
                <div id="preview-portada" class="curzilla-file-preview-container mt-3"></div>
            </fieldset>

            <footer class="curzilla-form-actions">
                <a href="/portal_cursos/public/instructor_router.php?action=dashboard" class="curzilla-btn curzilla-btn-secondary">Cancelar</a>
                <button type="submit" class="curzilla-btn curzilla-btn-primary">
                    <i class="fa-solid fa-save me-2"></i>Guardar Cambios
                </button>
            </footer>
        </form>
    </section>
</main>

<script src="/portal_cursos/public/assets/js/editar-curso.js"></script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>