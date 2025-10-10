<?php 
ob_start(); 
?>

    <!-- Hero Section -->
    <header class="hero">
        <div class="hero-background">
            <svg width="713" height="395" viewBox="0 0 713 395" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <ellipse cx="190.015" cy="197.479" rx="522.646" ry="196.588" transform="rotate(0.761974 190.015 197.479)" fill="#A996FF" fill-opacity="0.74"/>
            </svg>
        </div>
        <div class="hero-content">
            <div class="hero-left">
                <img src="/portal_cursos/public/assets/img/placeholders/Group 4.png" alt="A person pointing at a diagram with charts and graphs.">
            </div>
            
            <div class="hero-center">
                <div class="course-bubble">
                    Cursos Disponibles<br>
                    ¡Inscribete!
                </div>
                <div class="hero-girl">
                    <img src="/portal_cursos/public/assets/img/placeholders/Group.png" alt="A person programming on a laptop.">
                </div>
            </div>
            
            <div class="hero-right">
                <div class="hero-title">
                    <h1>Aprende rápido, trabaja pronto.</h1>
                </div>
                <p>Amplia gama de cursos tecnológicos cortos creados para potenciar tu trayectoria profesional actualizando tus conocimientos</p>
            </div>
        </div>
    </header>

    
    <main>
        <!-- Courses Section -->
        <section class="courses-section">
            <h2 class="courses-title">Todas las habilidades que necesitas en un unico Lugar!</h2>
            
            <div class="courses-grid">
                <?php if (empty($ultimosCursos)): ?>
                    <div class="no-courses">
                        <p>No hay cursos disponibles en este momento.</p>
                        <p>¡Pronto tendremos nuevos cursos para ti!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($ultimosCursos as $curso): ?>
                        <article class="course-card">
                            <a href="/portal_cursos/views/courses/detalle_curso.php?id=<?= $curso['id_curso'] ?>" class="course-link">
                                <img 
                                    src="<?= !empty($curso['portada']) ? htmlspecialchars($curso['portada']) : '/portal_cursos/public/assets/img/placeholders/course-default.png' ?>" 
                                    alt="<?= htmlspecialchars($curso['titulo']) ?>" 
                                    class="course-image"
                                    onerror="this.src='/portal_cursos/public/assets/img/placeholders/course-default.png'"
                                >
                            </a>
                            <div class="course-content">
                                <div class="course-header">
                                    <?php if (!empty($curso['categoria'])): ?>
                                        <span class="course-category"><?= htmlspecialchars($curso['categoria']) ?></span>
                                    <?php endif; ?>
                                    
                                    <span class="course-modality <?= strtolower($curso['modalidad']) ?>">
                                        <?= htmlspecialchars($curso['modalidad']) ?>
                                    </span>
                                </div>
                                
                                <h3 class="course-title">
                                    <a href="/portal_cursos/views/courses/detalle_curso.php?id=<?= $curso['id_curso'] ?>">
                                        <?= htmlspecialchars($curso['titulo']) ?>
                                    </a>
                                </h3>
                                
                                <p class="course-instructor">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                    <?= htmlspecialchars($curso['instructor']) ?>
                                </p>
                                
                                <footer class="course-stats">
                                    <div class="rating">
                                        <span class="star" aria-hidden="true">⭐</span>
                                        <span class="rating-value"><?= number_format($curso['promedio_calificacion'], 1) ?></span>
                                        <span class="rating-count">
                                            (<?php 
                                            $totalResenas = $curso['total_resenas'];
                                            if ($totalResenas >= 1000) {
                                                echo number_format($totalResenas / 1000, 1) . 'K';
                                            } else {
                                                echo $totalResenas;
                                            }
                                            ?>)
                                        </span>
                                    </div>
                                    
                                    <?php if (!empty($curso['duracion'])): ?>
                                        <span class="course-duration">
                                            <i class="fa-regular fa-clock"></i>
                                            <?= htmlspecialchars($curso['duracion']) ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($curso['total_inscritos'] > 0): ?>
                                        <span class="course-students">
                                            <i class="fa-solid fa-users"></i>
                                            <?= $curso['total_inscritos'] ?> <?= $curso['total_inscritos'] == 1 ? 'estudiante' : 'estudiantes' ?>
                                        </span>
                                    <?php endif; ?>
                                </footer>
                                
                                <div class="course-footer">
                                    <div class="course-price">
                                        <?php if ($curso['precio'] > 0): ?>
                                            <span class="price">Q<?= number_format($curso['precio'], 2) ?></span>
                                        <?php else: ?>
                                            <span class="price free">Gratis</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="/portal_cursos/views/courses/detalle_curso.php?id=<?= $curso['id_curso'] ?>" class="btn-ver-curso">
                                        Ver curso
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($ultimosCursos)): ?>
                <div class="ver-todos-cursos">
                    <a href="/portal_cursos/views/courses/cursos.php" class="btn-ver-todos">
                        Ver todos los cursos
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </main>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layouts/layout.php';
?>