<?php 
$projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/portal_cursos';
ob_start();
?>

<nav class="category-nav">
    <div class="category-container">
        <button class="category-button active">Todas</button>
        <button class="category-button">Tecnología</button>
        <button class="category-button">Negocios</button>
        <button class="category-button">Diseño</button>
        <button class="category-button">Marketing</button>
        <button class="category-button">Desarrollo Personal</button>
        <button class="category-button">Idiomas</button>
        <button class="category-button">Salud y Bienestar</button>
        <button class="category-button">Artes</button>
        <button class="category-button">Ciencias</button>
        <button class="category-button">Educación</button>
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
    <div class="curzilla-container">
        <section class="explore-section">
            <h2 class="explore-section-title">¡Los mejores cursos!</h2>

            <div class="courses-grid">
                <article class="course-card">
                    <img src="/portal_cursos/public/assets/img/placeholders/dan-nelson-AvSFPw5Tp68-unsplash%201.png" alt="Cyber Seguridad" class="course-image">
                    <div class="course-content">
                        <h4 class="course-title">Ciber Seguridad</h4>
                        <footer class="course-stats">
                            <div class="rating">
                                <span class="star" aria-hidden="true">⭐</span>
                                <span>4,5</span>
                            </div>
                            <span>16K calificaciones</span>
                            <span>Horas totales: 51.7</span>
                        </footer>
                    </div>
                </article>
                
                <article class="course-card">
                    <img src="/portal_cursos/public/assets/img/placeholders/james-harrison-vpOeXr5wmR4-unsplash%201.png" alt="Programación desde cero" class="course-image">
                    <div class="course-content">
                        <h4 class="course-title">Programación desde cero</h4>
                        <footer class="course-stats">
                            <div class="rating">
                                <span class="star" aria-hidden="true">⭐</span>
                                <span>4,7</span>
                            </div>
                            <span>18K calificaciones</span>
                            <span>Horas totales: 53.6</span>
                        </footer>
                    </div>
                </article>
                
                <article class="course-card">
                    <img src="/portal_cursos/public/assets/img/placeholders/hitesh-choudhary-D9Zow2REm8U-unsplash%201.png" alt="Lenguaje python" class="course-image">
                    <div class="course-content">
                        <h4 class="course-title">Lenguaje python</h4>
                        <footer class="course-stats">
                            <div class="rating">
                                <span class="star" aria-hidden="true">⭐</span>
                                <span>4,7</span>
                            </div>
                            <span>101K calificaciones</span>
                            <span>Horas totales: 161.1</span>
                        </footer>
                    </div>
                </article>
            </div>
        </section>
    </div>
</main>


<script>
    // Category navigation functionality
    function setupCategoryNavigation() {
        const categoryButtons = document.querySelectorAll('.category-button');
        categoryButtons.forEach(button => {
            button.addEventListener('click', () => {
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', () => {
        setupCategoryNavigation();
    });
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
</body>