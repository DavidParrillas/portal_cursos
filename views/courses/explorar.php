<?php 
$projectRoot = $_SERVER['DOCUMENT_ROOT'] . '/portal_cursos';
include $projectRoot . '/views/layouts/layout.php';
?>

<!-- Category Navigation -->
<nav class="category-nav">
    <div class="category-container">
        <button class="category-button active">Informática</button>
        <button class="category-button">Software</button>
        <button class="category-button">Contaduría</button>
        <button class="category-button">Marketing</button>
        <button class="category-button">Cocina</button>
        <button class="category-button">Diseño</button>
        <button class="category-button">Salud</button>
        <button class="category-button">Desarrollo personal</button>
        <button class="category-button">Música</button>
    </div>
</nav>

<!-- Page Title -->
        <br>
        <div class="explore-title-container">
            <h1 class="curzilla-page-title">Todos los mejores cursos de Informática</h1>
        </div>
        <!-- Hero Section2 -->
        <section class="explore-hero-section">
            <!-- Illustration Section -->
            <div class="explore-hero-content">
                <div class="explore-hero-illustration">
                    <img src="/portal_cursos/public/assets/img/placeholders/undraw_developer-activity_4zqd%201.png" alt="Ilustración de un desarrollador">
                </div>

                <div class="explore-hero-text-box">
                    <div class="text-content">
                        <h2>Aprende en la mejor plataforma de cursos profesionales</h2>
                        <p>Te tenemos los mejores cursos de informática para que alcances tu meta lo más rápido posible.</p>
                        <p>¡VAMOS!</p>
                    </div>
                </div>
            </div>
        </section>

<!-- Main Content -->
<main class="curzilla-main-content explore-page-container">
    <div class="curzilla-container">
        <!-- Best Courses Section -->
        <section class="explore-section">
            <div>
                <h2 class="explore-section-title">¡Los mejores cursos de Informática Aquí!</h2>
            </div>

            <div>
                <h3 class="explore-subsection-title">+ Mas vendidos</h3>
                <div class="courses-grid">
                    <div class="course-card">
                        <img src="/portal_cursos/public/assets/img/placeholders/dan-nelson-AvSFPw5Tp68-unsplash%201.png" alt="Cyber Seguridad" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">Ciber Seguridad</h3>
                            <div class="course-stats">
                                <div class="rating">
                                    <span class="star">⭐</span>
                                    <span>4,5</span>
                                </div>
                                <span>16K calificaciones</span>
                                <span>Horas totales: 51.7</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="course-card">
                        <img src="/portal_cursos/public/assets/img/placeholders/james-harrison-vpOeXr5wmR4-unsplash%201.png" alt="Programación desde cero" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">Programación desde cero</h3>
                            <div class="course-stats">
                                <div class="rating">
                                    <span class="star">⭐</span>
                                    <span>4,7</span>
                                </div>
                                <span>18K calificaciones</span>
                                <span>Horas totales: 53.6</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="course-card">
                        <img src="/portal_cursos/public/assets/img/placeholders/hitesh-choudhary-D9Zow2REm8U-unsplash%201.png" alt="Lenguaje python" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">Lenguaje python</h3>
                            <div class="course-stats">
                                <div class="rating">
                                    <span class="star">⭐</span>
                                    <span>4,7</span>
                                </div>
                                <span>101K calificaciones</span>
                                <span>Horas totales: 161.1</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recommended Section -->
        <section class="explore-section">
            <h3 class="explore-subsection-title">Recomendados</h3>
            <div class="courses-grid">
                <div class="course-card">
                    <img src="/portal_cursos/public/assets/img/placeholders/dan-nelson-AvSFPw5Tp68-unsplash%201.png" alt="Cyber Seguridad" class="course-image">
                    <div class="course-content">
                        <h3 class="course-title">Ciber Seguridad</h3>
                        <div class="course-stats">
                            <div class="rating">
                                <span class="star">⭐</span>
                                <span>4,5</span>
                            </div>
                            <span>16K calificaciones</span>
                            <span>Horas totales: 51.7</span>
                        </div>
                    </div>
                </div>
                
                <div class="course-card">
                    <img src="/portal_cursos/public/assets/img/placeholders/james-harrison-vpOeXr5wmR4-unsplash%201.png" alt="Programación desde cero" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">Programación desde cero</h3>
                            <div class="course-stats">
                                <div class="rating">
                                    <span class="star">⭐</span>
                                    <span>4,7</span>
                                </div>
                                <span>18K calificaciones</span>
                                <span>Horas totales: 53.6</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="course-card">
                        <img src="/portal_cursos/public/assets/img/placeholders/hitesh-choudhary-D9Zow2REm8U-unsplash%201.png" alt="Lenguaje python" class="course-image">
                        <div class="course-content">
                            <h3 class="course-title">Lenguaje python</h3>
                            <div class="course-stats">
                                <div class="rating">
                                    <span class="star">⭐</span>
                                    <span>4,7</span>
                                </div>
                                <span>101K calificaciones</span>
                                <span>Horas totales: 161.1</span>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
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
</body>