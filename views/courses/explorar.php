<?php 
require_once __DIR__ . '/../../models/Curso.php';
require_once __DIR__ . '/../../models/Categoria.php';
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::getInstance();
$cursoModel = new Curso($pdo);
$categoriaModel = new Categoria($pdo);
$categorias = $categoriaModel->obtenerTodas();

$projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/portal_cursos';
ob_start();
?>

<nav class="category-nav">
    <div class="category-container">
        <button class="category-button active" data-id="0">Todas</button>
        <?php foreach ($categorias as $categoria): ?>
            <button class="category-button" data-id="<?= $categoria['id_categoria'] ?>">
                <?= htmlspecialchars($categoria['nombre']) ?>
            </button>
        <?php endforeach; ?>
    </div>
</nav>

    <header class="explore-hero-section">
        <div class="explore-hero-content">
            <div class="explore-hero-illustration">
                <img src="/portal_cursos/public/assets/img/placeholders/undraw_developer-activity_4zqd%201.png" alt="Ilustración de un desarrollador">
            </div>
            <div class="explore-hero-text-box">
                <div class="text-content">
                    <h1 class="curzilla-page-title">Todos los mejores cursos de Informática</h1>
                    <h2>Aprende en la mejor plataforma de cursos profesionales</h2>
                    <p>Te tenemos los mejores cursos de informática para que alcances tu meta lo más rápido posible.</p>
                    <p>¡VAMOS!</p>
                </div>
            </div>
        </div>
    </header>

<main class="curzilla-main-content-explore-page-container">
    <!-- Courses Section -->
        <section class="courses-section">
            <h2 class="courses-title">Todas las habilidades que necesitas en un unico Lugar!</h2>
            
            <div class="courses-grid">
                <!-- Los cursos se cargarán aquí dinámicamente -->
            </div>
        </section>
</main>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const coursesGrid = document.querySelector('.courses-grid');
        const categoryButtons = document.querySelectorAll('.category-button');

        // Función para renderizar los cursos
        function renderCursos(cursos) {
            coursesGrid.innerHTML = ''; // Limpiar el grid
            if (cursos.length === 0) {
                coursesGrid.innerHTML = `
                    <div class="no-courses">
                        <p>No hay cursos disponibles en esta categoría en este momento.</p>
                        <p>¡Pronto tendremos nuevos cursos para ti!</p>
                    </div>`;
                return;
            }

            cursos.forEach(curso => {
                const cursoCard = document.createElement('article');
                cursoCard.className = 'course-card';

                const precio = curso.precio > 0 ? `$${parseFloat(curso.precio).toFixed(2)}` : 'Gratis';
                const portada = curso.portada ? curso.portada : '/portal_cursos/public/assets/img/placeholders/course-default.png';

                let totalResenas = curso.total_resenas;
                if (totalResenas >= 1000) {
                    totalResenas = (totalResenas / 1000).toFixed(1) + 'K';
                }

                cursoCard.innerHTML = `
                    <a href="/portal_cursos/views/courses/detalle_curso.php?id=${curso.id_curso}" class="course-link">
                        <img 
                            src="${portada}" 
                            alt="${curso.titulo}" 
                            class="course-image"
                            onerror="this.src='/portal_cursos/public/assets/img/placeholders/course-default.png'"
                        >
                    </a>
                    <div class="course-content">
                        <h3 class="course-title">
                            <a href="/portal_cursos/views/courses/detalle_curso.php?id=${curso.id_curso}">${curso.titulo}</a>
                        </h3>
                        <p class="course-instructor">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            ${curso.instructor}
                        </p>
                        <footer class="course-stats">
                            <div class="rating">
                                <span class="star" aria-hidden="true">⭐</span>
                                <span class="rating-value">${parseFloat(curso.promedio_calificacion).toFixed(1)}</span>
                                <span class="rating-count">(${totalResenas})</span>
                            </div>
                            ${curso.duracion ? `<span class="course-duration"><i class="fa-regular fa-clock"></i> ${curso.duracion}</span>` : ''}
                            ${curso.total_inscritos > 0 ? `<span class="course-students"><i class="fa-solid fa-users"></i> ${curso.total_inscritos} ${curso.total_inscritos == 1 ? 'estudiante' : 'estudiantes'}</span>` : ''}
                        </footer>
                        <div class="course-footer">
                            <div class="course-price">
                                <span class="price ${precio === 'Gratis' ? 'free' : ''}">${precio}</span>
                            </div>
                            <a href="/portal_cursos/views/courses/detalle_curso.php?id=${curso.id_curso}" class="btn btn-primary">Ver curso</a>
                        </div>
                    </div>
                `;
                coursesGrid.appendChild(cursoCard);
            });
        }

        // Función para cargar cursos
        async function cargarCursos(idCategoria = 0) {
            try {
                const response = await fetch(`/portal_cursos/controllers/CursoController.php?action=listarPorCategoria&id_categoria=${idCategoria}`);
                if (!response.ok) {
                    throw new Error('La respuesta de la red no fue exitosa.');
                }
                const cursos = await response.json();
                renderCursos(cursos);
            } catch (error) {
                console.error('Error al cargar los cursos:', error);
                coursesGrid.innerHTML = '<p>Error al cargar los cursos. Por favor, intenta de nuevo más tarde.</p>';
            }
        }

        // Event listeners para los botones de categoría
        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const idCategoria = button.dataset.id;
                cargarCursos(idCategoria);
            });
        });

        // Carga inicial de todos los cursos
        cargarCursos();
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>