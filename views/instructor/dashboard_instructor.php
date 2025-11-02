<?php
/**
 * Vista del Dashboard del Instructor
 * Muestra los cursos del instructor y estadísticas
 */

include __DIR__ . '/../layouts/layout.php';

$cursos = $cursos ?? [];
$estadisticas = $estadisticas ?? [
    'total_cursos' => 0,
    'total_estudiantes' => 0,
    'ingresos_totales' => 0
];
?>

<!-- Estilos específicos del dashboard del instructor -->
<link rel="stylesheet" href="/portal_cursos/public/css/styles.css">

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
                <h3>No tienes ningún curso agregado</h3>
            </div>
        <?php else: ?>
            <!-- Lista de cursos -->
            <?php foreach ($cursos as $curso): ?>
                <div class="course-card">
                    <div class="course-header">
                        <div class="course-image">
                            🐍
                        </div>
                        <div class="course-info">
                            <!-- Agregando badge de estado del curso -->
                            <?php
                            $estado = strtolower($curso['estado']);
                            $estadoClass = 'status-' . str_replace(' ', '-', $estado);
                            ?>
                            <span class="course-status-badge <?php echo $estadoClass; ?>">
                                <?php echo htmlspecialchars($curso['estado']); ?>
                            </span>
                            
                            <div class="course-title"><?php echo htmlspecialchars($curso['titulo']); ?></div>
                            <div class="course-instructor"><?php echo htmlspecialchars($curso['nombre_instructor']); ?></div>
                            <div class="course-rating">
                                <span class="stars">
                                    <?php
                                    $rating = round($curso['calificacion_promedio'], 1);
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '★' : '☆';
                                    }
                                    ?>
                                </span>
                                <span class="rating-count"><?php echo number_format($rating, 1); ?> (<?php echo $curso['total_inscritos']; ?>)</span>
                            </div>
                            <div class="course-price">$<?php echo number_format($curso['precio'], 2); ?> US</div>
                        </div>
                    </div>

                    <!-- Detalles del curso -->
                    <div class="course-details">
                        <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                        <p class="course-description">
                            <?php echo htmlspecialchars($curso['descripcion']); ?>
                        </p>

                        <div class="course-content-header">Contenido</div>
                        <table class="content-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th>Título</th>
                                    <th style="width: 150px;">Duración</th>
                                    <th style="width: 200px;">Archivos subidos:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <svg class="play-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg>
                                    </td>
                                    <td>Introducción curso <?php echo htmlspecialchars($curso['titulo']); ?></td>
                                    <td>Duración: <?php echo htmlspecialchars($curso['duracion'] ?? '05:45'); ?></td>
                                    <td><a href="#" class="file-link">Archivo adjunto</a></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Botones de acción -->
                        <div class="action-buttons">
                            <button class="btn btn-delete" onclick="eliminarCurso(<?php echo $curso['id']; ?>)">
                                Eliminar curso
                            </button>
                            <button class="btn btn-edit" onclick="editarCurso(<?php echo $curso['id']; ?>)">
                                Editar Curso
                            </button>
                            <button class="btn btn-publish" onclick="publicarCurso(<?php echo $curso['id']; ?>, <?php echo $curso['publicado']; ?>)">
                                <?php echo $curso['publicado'] ? 'Despublicar' : 'Publicar'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Sección de Cursos para ti -->
    <div class="recommended-section">
        <h2>Cursos para ti</h2>
        <div class="recommended-grid">
            <div class="recommended-card">
                <div class="recommended-image" style="background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                    <img src="/portal_cursos/public/assets/img/placeholders/AP_Course.jpg" alt="Ilustración de curso Adobe Premiere" width="373px" height="160px">
                </div>
                <div class="recommended-info">
                    <div class="recommended-title">Adobe Premiere</div>
                    <div class="course-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                    <div class="recommended-price">$29.99 US</div>
                </div>
            </div>

            <div class="recommended-card">
                <div class="recommended-image" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                    <img src="/portal_cursos/public/assets/img/placeholders/Canva_design.jpg" alt="Ilustración de curso Adobe Premiere" width="373px" height="160px">
                </div>
                <div class="recommended-info">
                    <div class="recommended-title">Canva Pro</div>
                    <div class="course-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                    <div class="recommended-price">$15.99 US</div>
                </div>
            </div>

            <div class="recommended-card">
                <div class="recommended-image" style="background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                    <img src="/portal_cursos/public/assets/img/placeholders/PS_Course.jpg" alt="Ilustración de curso Adobe Premiere" width="373px" height="160px">
                </div>
                <div class="recommended-info">
                    <div class="recommended-title">Photoshop P</div>
                    <div class="course-rating">
                        <span class="stars">★★★★★</span>
                    </div>
                    <div class="recommended-price">$19.99 US</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts JavaScript para las funciones del dashboard -->
<script>
    // Función para eliminar curso
    function eliminarCurso(cursoId) {
        if (confirm('¿Estás seguro de que deseas eliminar este curso?')) {
            // Aquí iría la lógica para eliminar el curso
            alert('Funcionalidad de eliminar curso en desarrollo');
        }
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
