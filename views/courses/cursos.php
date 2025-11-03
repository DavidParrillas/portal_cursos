
<?php
session_start();

$pageTitle = "Cursos - Curzilla";
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::getInstance();

// ===================================================
// 🔹 SIMULACIÓN TEMPORAL DE CURSOS INSCRITOS (para pruebas visuales)
// ===================================================
$cursosUsuario = [
    [
        'id_curso' => 1,
        'titulo' => 'Curso de Python Profesional',
        'descripcion' => 'Aprende Python desde cero hasta avanzado.',
        'portada' => '/portal_cursos/public/assets/img/misCursos/pythonc.png',
        'duracion' => '10 horas',
        'precio' => 29.99,
        'modalidad' => 'En línea',
        'fecha_inicio' => '2025-10-15',
        'fecha_fin' => '2025-12-30',
        'progreso' => 45
    ],
    [
        'id_curso' => 2,
        'titulo' => 'Java para principiantes',
        'descripcion' => 'Domina los fundamentos de Java.',
        'portada' => '/portal_cursos/public/assets/img/misCursos/javac.png',
        'duracion' => '8 horas',
        'precio' => 35.99,
        'modalidad' => 'En línea',
        'fecha_inicio' => '2025-11-10',
        'fecha_fin' => '2026-01-20',
        'progreso' => 0
        
    ],
    [
        'id_curso' => 3,
        'titulo' => 'HTML completo',
        'descripcion' => 'Diseña sitios web modernos con HTML5 y CSS3.',
        'portada' => '/portal_cursos/public/assets/img/curso_html.png',
        'duracion' => '6 horas',
        'precio' => 25.99,
        'modalidad' => 'Presencial',
        'fecha_inicio' => '2025-09-01',
        'fecha_fin' => '2025-11-10',
        'progreso' => 90
    ],
];


ob_start();
?>

<main class="home-section">
    <!-- Hero -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Aprende en la mejor plataforma de cursos profesionales</h1>
                <p>Te ofrecemos los mejores cursos de informática con instructores certificados y acceso de por vida.</p>
                <a href="courses.php" class="btn btn-primary">Explorar Cursos</a>
            </div>
            <div class="hero-image">
                <img src="/portal_cursos/public/assets/img/misCursos/cohete.png" alt="Aprendiendo en línea">
            </div>
        </div>
    </section>

    <!-- Encabezado de sección -->
   <section class="section-header">
  <div class="header-inner">
    <h2>¡Los mejores cursos de <span>Informática</span> Aquí!</h2>
    <p>Explora cursos diseñados por expertos y mejora tus habilidades profesionales</p>
  </div>
</section>




    <!-- Mis cursos (nuestros cursos del usuario) -->
    <section class="courses-section">
         <div class="courses-header"></div>
    
        <h3>+ Mis Cursos</h3>
        <div class="search-box">
            <i class="fa fa-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="     Buscar curso...">
        </div>
        <div class="courses-grid">
            <?php foreach ($cursosUsuario as $curso): ?>
                <?php
                    // 🧮 Calcular estado del curso según fechas
                    $hoy = date('Y-m-d');
                    $estado = '';
                    $color = '';

                    if ($hoy < $curso['fecha_inicio']) {
                        $estado = 'Próximo';
                        $color = 'blue';
                    } elseif ($hoy >= $curso['fecha_inicio'] && $hoy <= $curso['fecha_fin']) {
                        $estado = 'Activo';
                        $color = 'green';
                    } else {
                        $estado = 'Finalizado';
                        $color = 'gray';
                    }
                ?>
                <article class="course-card">
                    <img src="<?= htmlspecialchars($curso['portada']) ?>" alt="<?= htmlspecialchars($curso['titulo']) ?>" class="course-image">

                    <div class="course-content">
                        <h4 class="course-title"><?= htmlspecialchars($curso['titulo']) ?></h4>

                        <div class="course-teacher">
                            <i class="fa-solid fa-user"></i> Mario Diaz
                        </div>
                         

                        <div class="course-info">
                            <div class="course-rating">
                                <i class="fa-solid fa-star"></i> 0.0 <span>(0)</span>
                            </div>
                            <div class="course-duration">
                                <i class="fa-regular fa-clock"></i> <?= htmlspecialchars($curso['duracion']) ?>
                            </div>
                        </div>

                        

                        <!-- Estado visual -->
                        <div class="course-status <?= $color ?>">
                            <?= $estado ?>
                        </div>

                        <a href="detalleCurso.php?id=<?= urlencode($curso['id_curso']) ?>" class="btn-vercurso">Ver curso</a>
                    </div>
                    <!-- Barra de progreso -->
<div class="progress-container">
    <div class="progress-bar" style="width: <?= $curso['progreso'] ?>%;">
        <?= $curso['progreso'] ?>%
    </div>
</div>

                </article>
            <?php endforeach; ?>
        </div>
    </section>

   <!-- Recomendados -->
<section class="courses-section">
  <h3>Recomendados</h3>
  <div class="courses-grid">
    
    <!-- Card 1 -->
    <article class="course-card">
      <img src="/portal_cursos/public/assets/img/misCursos/adobec.png" alt="Curso Adobe Premiere" class="course-image">
      <div class="course-content">
        <h4 class="course-title">Curso de Adobe Premiere</h4>

        <div class="course-teacher">
          <i class="fa-solid fa-user"></i> Mario
        </div>

        <div class="course-info">
          <div class="course-rating">
            <i class="fa-solid fa-star"></i> 0.0 <span>(0)</span>
          </div>
          <div class="course-duration">
            <i class="fa-regular fa-clock"></i> 2 secciones, 3 clases, 5 horas
          </div>
        </div>

        <span class="course-price">$44.99 US</span>
        <a href="detalleCurso.php?id=premiere" class="btn-vercurso">Ver curso</a>
      </div>
    </article>

    <!-- Card 2 -->
    <article class="course-card">
      <img src="/portal_cursos/public/assets/img/misCursos/canvas.png" alt="Curso Canva" class="course-image">
      <div class="course-content">
        <h4 class="course-title">Canva Pro</h4>

        <div class="course-teacher">
          <i class="fa-solid fa-user"></i> Mario
        </div>

        <div class="course-info">
          <div class="course-rating">
            <i class="fa-solid fa-star"></i> 0.0 <span>(0)</span>
          </div>
          <div class="course-duration">
            <i class="fa-regular fa-clock"></i> 2 secciones, 3 clases, 5 horas
          </div>
        </div>

        <span class="course-price">$29.99 US</span>
        <a href="detalleCurso.php?id=canva" class="btn-vercurso">Ver curso</a>
      </div>
    </article>

    <!-- Card 3 -->
    <article class="course-card">
      <img src="/portal_cursos/public/assets/img/misCursos/Photoshop.png" alt="Curso Photoshop" class="course-image">
      <div class="course-content">
        <h4 class="course-title">Photoshop Pro</h4>

        <div class="course-teacher">
          <i class="fa-solid fa-user"></i> Mario
        </div>

        <div class="course-info">
          <div class="course-rating">
            <i class="fa-solid fa-star"></i> 0.0 <span>(0)</span>
          </div>
          <div class="course-duration">
            <i class="fa-regular fa-clock"></i> 2 secciones, 3 clases, 5 horas
          </div>
        </div>

        <span class="course-price">$29.99 US</span>
        <a href="detalleCurso.php?id=photoshop" class="btn-vercurso">Ver curso</a>
      </div>
    </article>

  </div>
</section>

<!-- Script para búsqueda en tiempo real -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('searchInput');
    const courses = document.querySelectorAll('.courses-section .course-card');

    input.addEventListener('keyup', function() {
        const term = input.value.toLowerCase();

        courses.forEach(card => {
            const title = card.querySelector('.course-title').textContent.toLowerCase();
            const instructor = card.querySelector('.course-teacher')?.textContent.toLowerCase() || '';
            
            if (title.includes(term) || instructor.includes(term)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
<script src="/portal_cursos/public/assets/js/inicio.js"></script>

<?php

$content = ob_get_clean();


include __DIR__ . '/../layouts/layout.php';
?>
