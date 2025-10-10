<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Crear Curso - Curzilla";
ob_start();

// Asegurarse de que el usuario esté autenticado y sea un instructor
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'instructor') {
    header('Location: /portal_cursos/index.php');
    exit;
}

// Obtener categorías disponibles
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Categoria.php';

$pdo = Database::getInstance();
$categoriaModel = new Categoria($pdo);
$categorias = $categoriaModel->obtenerTodas();
?>

<!-- Main Content -->
<main class="curzilla-main-content">
    <section class="curzilla-container">
        <h1 class="curzilla-page-title">Crear Nuevo Curso</h1>

        <form class="curzilla-course-form" method="POST" action="/portal_cursos/controllers/CursoController.php?action=crear" enctype="multipart/form-data" id="form-crear-curso">
            <fieldset>
                <legend class="sr-only">Detalles del Curso</legend>
                <div class="curzilla-form-row">
                    <div class="curzilla-form-group">
                        <label for="course-title">Título del curso</label>
                        <input type="text" id="course-title" name="titulo" class="curzilla-form-input" required>
                    </div>
                    <div class="curzilla-form-group" id="participants-group">
                        <label for="max-participants">Máximo de participantes</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="max-participants">-</button>
                            <input type="number" id="max-participants" name="cupos" value="0" min="0" class="curzilla-counter-value" required>
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="max-participants">+</button>
                        </div>
                    </div>
                </div>
                <br>
                <div class="curzilla-form-group">
                    <label for="course-description">Descripción del Curso</label>
                    <textarea id="course-description" name="descripcion" class="curzilla-form-textarea" rows="6" required></textarea>
                </div>

                <br>
                <div class="curzilla-form-group">
                    <label for="categorias">Categoría</label>
                    <select id="categorias" name="categorias[]" class="curzilla-form-select" required>
                        <option value="">Seleccionar categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id_categoria'] ?>">
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
                            <input type="number" id="sections" name="secciones" value="0" min="0" class="curzilla-counter-value curzilla-purple">
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="sections">+</button>
                        </div>
                    </div>
                    
                    <div class="curzilla-form-group">
                        <label for="classes">Clases</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="classes">-</button>
                            <input type="number" id="classes" name="clases" value="0" min="0" class="curzilla-counter-value curzilla-purple">
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="classes">+</button>
                        </div>
                    </div>
                    
                    <div class="curzilla-form-group">
                        <label for="hours">Horas</label>
                        <div class="curzilla-counter-input">
                            <button type="button" class="curzilla-counter-btn" data-action="decrease" data-target="hours">-</button>
                            <input type="number" id="hours" name="horas" value="0" min="0" class="curzilla-counter-value curzilla-purple">
                            <button type="button" class="curzilla-counter-btn" data-action="increase" data-target="hours">+</button>
                        </div>
                    </div>
                    
                    <div class="curzilla-form-group">
                        <label for="fecha-inicio">Fecha de Inicio</label>
                        <input type="date" id="fecha-inicio" name="fecha_inicio" class="curzilla-form-input">
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
                                <option value="PRESENCIAL">Presencial</option>
                                <option value="VIRTUAL">Virtual</option>
                                <option value="HIBRIDA">Híbrida</option>
                            </select>
                        </div>
                    </div>
                    <div class="curzilla-form-group">
                        <label for="price">
                            Precio
                        </label>
                        <input type="number" id="price" name="precio" placeholder="0.00" step="0.01" min="0" class="curzilla-form-input curzilla-price-input" required>
                    </div>
                </div>
            </fieldset>
                        
            <fieldset>
                <legend class="curzilla-section-title">Agregar videos de YouTube</legend>
                <div id="videos-container"  class="curzilla-upload-section">
                    <div class="video-item">
                        <div class="curzilla-form-group">
                            <label>Título del video</label>
                            <input type="text" name="videos[0][titulo]" class="curzilla-form-input" placeholder="Ejemplo: Introducción al curso">
                        </div>
                        <div class="curzilla-form-group">
                            <label>URL de YouTube</label>
                            <input type="url" name="videos[0][url]" class="curzilla-form-input" placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <button type="button" class="curzilla-btn curzilla-btn-danger btn-remove-video" style="display: none;">Eliminar video</button>
                    </div>
                </div>
                <button type="button" class="curzilla-btn curzilla-btn-secondary" id="btn-add-video">+ Agregar otro video</button>
            </fieldset>
            
            <fieldset >
                <legend class="curzilla-section-title">Adjuntar archivos de apoyo</legend>
                <div class="curzilla-upload-section">
                    <div class="curzilla-upload-area">
                    <div class="curzilla-upload-content">
                        <div class="curzilla-upload-icon" aria-hidden="true"><i class="fa-solid fa-file-arrow-up"></i></div>
                        <p class="curzilla-upload-text">Archivos permitidos: PDF, PNG, JPG (Máx: 5MB)</p>
                        <input type="file" id="archivos" name="archivos[]" multiple accept=".pdf,.png,.jpg,.jpeg" style="display: none;">
                        <button type="button" class="curzilla-upload-btn" onclick="document.getElementById('archivos').click()">+ Subir archivos</button>
                    </div>
                    <div id="archivos-seleccionados"></div>
                </div>
                </div>
            </fieldset>
            
            <fieldset>
                <legend class="curzilla-section-title">Imagen de portada</legend>
                <div class="curzilla-upload-section">
                    <div class="curzilla-upload-area">
                    <div class="curzilla-upload-content">
                        <div class="curzilla-upload-icon" aria-hidden="true"><i class="fa-solid fa-file-arrow-up"></i></div>
                        <p class="curzilla-upload-text">Archivos permitidos: PNG, JPG (Máx: 5MB)</p>
                        <input type="file" id="portada" name="portada" accept=".png,.jpg,.jpeg" style="display: none;" required>
                        <button type="button" class="curzilla-upload-btn" onclick="document.getElementById('portada').click()">+ Subir imagen</button>
                    </div>
                    <div id="preview-portada"></div>
                </div>
                </div>
            </fieldset>

            <footer class="curzilla-form-actions">
                <button type="button" class="curzilla-btn curzilla-btn-secondary" onclick="limpiarFormulario()">Limpiar</button>
                <button type="button" class="curzilla-btn curzilla-btn-secondary" name="estado" value="BORRADOR" onclick="guardarComo('BORRADOR')">Guardar como Borrador</button>
                <button type="submit" class="curzilla-btn curzilla-btn-primary" name="estado" value="PENDIENTE">Enviar para Revisión</button>
            </footer>
            <input type="hidden" name="estado" id="estado-curso" value="PENDIENTE">
        </form>
    </section>
</main>

<script src="/portal_cursos/public/assets/js/crear-curso.js"></script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>