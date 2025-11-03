<?php
/**
 * Vista del Dashboard del Instructor
 * Muestra los cursos del instructor y estadísticas
 */
ob_start();

// Inicializar variables
$cursos = $cursos ?? [];
$estadisticas = $estadisticas ?? [
    'total_cursos' => 0,
    'total_estudiantes' => 0,
    'ingresos_totales' => 0
];

error_log("VISTA CARGADA - Cursos: " . count($cursos));

// Evitar acceso directo a la vista: si se accede a este archivo directamente redirigir al router
if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    // Se accedió directamente al archivo de vista. Redirigimos al router para que prepare datos.
    header('Location: /portal_cursos/public/instructor_router.php?action=dashboard');
    exit;
}
?>

<?php if (empty($cursos)): ?>
    <div style="background:#fff3cd;border:1px solid #ffeeba;padding:12px;border-radius:6px;margin:12px 0;color:#856404;">
        <strong>Depuración:</strong>
        <div>Instructor en sesión: <?php echo htmlspecialchars($_SESSION['user_id'] ?? 'no-session'); ?> (<?php echo htmlspecialchars($_SESSION['user_nombre'] ?? 'sin-nombre'); ?>)</div>
        <div>Conteo de cursos recibido por la vista: <?php echo count($cursos); ?></div>
        <details style="margin-top:8px;"><summary>Ver contenido bruto de <code>$cursos</code></summary>
            <pre style="white-space:pre-wrap;max-height:300px;overflow:auto;background:#fff;border:1px solid #e6e6e6;padding:8px;">
<?php echo htmlspecialchars(print_r($cursos, true)); ?>
            </pre>
        <div style="margin-top:8px;color:#333;font-size:0.9rem;">Si aquí aparecen cursos, el problema está en el render; si está vacío, el modelo no devolvió filas.</div>
    </div>
<?php endif; ?>

<!-- Estilos específicos del dashboard del instructor -->
<link rel="stylesheet" href="/portal_cursos/public/css/styles.css">
<link rel="stylesheet" href="/portal_cursos/public/assets/css/dashboard_instructor.css">

<!-- Contenido principal del dashboard -->
<div class="container">
    <!-- Header de bienvenida -->
    <div class="welcome-header">
        <div class="welcome-content">
            <h1>¡Bienvenido nuevo tutor a Curzilla!</h1>
            <p>Quien se atreve a enseñar, nunca debe dejar de aprender</p>
            <p style="margin-top: 10px; font-size: 14px;">Formando a los mejores estudiantes para formar profesional, así estarás en el lugar indicado.</p>
        </div>
        <div class="welcome-illustration">
            <img src="/portal_cursos/public/assets/img/placeholders/undraw_developer-activity_4zqd 1.png" alt="Ilustración de bienvenida para instructores" width="250px" height="200px">
        </div>
    </div>

    <!-- Agregando panel de estadísticas rápidas -->
    <div class="stats-panel">
        <div class="stat-card">
            <div class="stat-icon purple">📚</div>
            <div class="stat-label">Total de Cursos</div>
            <div class="stat-value"><?php echo number_format($estadisticas['total_cursos']); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">👥</div>
            <div class="stat-label">Total de Estudiantes</div>
            <div class="stat-value"><?php echo number_format($estadisticas['total_estudiantes']); ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">💰</div>
            <div class="stat-label">Ingresos Totales</div>
            <div class="stat-value">$<?php echo number_format($estadisticas['ingresos_totales'], 2); ?></div>
        </div>
    </div>

    <!-- Botón Enseña Aquí -->
    <div class="teach-button">
        ¡Enseña Aquí!
    </div>

    <!-- Sección de Tus Cursos -->
    <div class="courses-section">
        <div class="section-header">
            <h2>+Tus cursos</h2>
            <button class="add-button" onclick="window.location.href='/portal_cursos/public/instructor_router.php?action=crearCurso'">
                <span style="font-size: 20px;">+</span> Agregar
            </button>
        </div>

        <?php if (empty($cursos)): ?>
            <!-- Estado vacío -->
            <div class="empty-state">
                <div class="empty-state-image">
                    <img src="\portal_cursos\public\assets\img\placeholders\undraw_monitor_ypga 1.png" alt="No hay cursos" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <h3>Aún no has creado ningún curso.</h3>
                <p>¡Anímate a crear tu primer curso y compartir tu conocimiento!</p>
                <button class="add-button" onclick="window.location.href='/portal_cursos/public/instructor_router.php?action=crearCurso'">
                    <span style="font-size: 20px;">+</span> Crear mi primer curso
                </button>
            </div>
        <?php else: ?>
            <!-- Lista de cursos -->
            <div class="courses-grid">
                <?php 
                error_log("Renderizando cursos en la vista: " . print_r($cursos, true));
                foreach ($cursos as $curso): 
                    error_log("Procesando curso: " . print_r($curso, true));
                ?>
                    <div class="course-card">
                        <div class="course-header">
                            <div class="course-image">
                                <?php if (!empty($curso['portada'])): ?>
                                    <img src="<?= htmlspecialchars($curso['portada']) ?>" alt="<?= htmlspecialchars($curso['titulo']) ?>">
                                <?php else: ?>
                                    <div class="course-placeholder">📚</div>
                                <?php endif; ?>
                            </div>
                            <div class="course-info">
                                <?php
                                $estado = strtolower($curso['estado']);
                                $estadoClass = 'status-' . str_replace(' ', '-', $estado);
                                ?>
                                <span class="course-status-badge <?php echo $estadoClass; ?>">
                                    <?php echo htmlspecialchars($curso['estado']); ?>
                                </span>
                                
                                <h3 class="course-title"><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                <div class="course-category"><?php echo htmlspecialchars($curso['categoria'] ?? 'Sin categoría'); ?></div>
                                
                                <div class="course-meta">
                                    <div class="course-rating">
                                        <span class="stars">
                                            <?php
                                            $rating = round($curso['calificacion_promedio'], 1);
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $rating ? '★' : '☆';
                                            }
                                            ?>
                                        </span>
                                        <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                                    </div>
                                    <div class="course-students">
                                        <span class="students-icon">👥</span>
                                        <span><?php echo $curso['total_inscritos']; ?> estudiantes</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <div class="course-price">$<?php echo number_format($curso['precio'], 2); ?> USD</div>
                                    <div class="course-actions">
                                        <button class="btn btn-edit" onclick="window.location.href='/portal_cursos/views/courses/editarCurso.php?id=<?php echo $curso['id_curso']; ?>'">
                                            ✏️ Editar
                                        </button>
                                        <button class="btn btn-delete" onclick="eliminarCurso(<?php echo $curso['id_curso']; ?>)">
                                            🗑️ Archivar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<!-- Scripts JavaScript para las funciones del dashboard -->
<script>
    // Función para eliminar curso
    function eliminarCurso(cursoId) {
        alertify.confirm('Archivar Curso', '¿Estás seguro de que deseas archivar este curso? El curso se ocultará pero no se eliminará permanentemente.', 
            function(){ 
                fetch('/portal_cursos/public/instructor_router.php?action=eliminarCurso', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_curso: cursoId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alertify.success(data.mensaje);
                        // Recargar la página después de 1.5 segundos para ver el cambio
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        alertify.error('Error: ' + data.mensaje);
                    }
                })
                .catch(error => {
                    alertify.error('Hubo un problema con la solicitud.');
                    console.error('Error:', error);
                });
            }, 
            function(){ alertify.error('Acción cancelada') }
        ).set('labels', {ok:'Sí, archivar', cancel:'Cancelar'});
    }

    // Función para editar curso
    function editarCurso(cursoId) {
        window.location.href = '/portal_cursos/views/instructor/editar_curso.php?id=' + cursoId;
    }

    // Función para publicar/despublicar curso
    function publicarCurso(cursoId, estadoActual) {
        const accion = estadoActual ? 'despublicar' : 'publicar';
        if (confirm(`¿Estás seguro de que deseas ${accion} este curso?`)) {
            // Aquí iría la lógica para cambiar el estado
            alert(`Funcionalidad de ${accion} curso en desarrollo`);
        }
    }
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
