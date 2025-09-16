<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curzilla - Plataforma de Cursos Online</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/portal_cursos/public/assets/css/curzilla.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/portal_cursos/public/assets/css/styles.css?v=<?php echo time(); ?>">
</head>

<!-- Nav -->
<nav class="header">
    <button class="nav-toggle-btn" id="nav-toggle-btn">
        <i class="fa-solid fa-bars"></i>
    </button>
    <div class="logo">
        <!-- Logo with link to home -->
        <a href="/portal_cursos/public/index.php">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-g55lksb0cwSrjGGG1q02lKfSWJACu1.png" alt="Curzilla Logo">
        </a>
    </div>
    <div class="nav-container" id="nav-container">
        <div class="nav-center">
            <a href="/portal_cursos/views/courses/explorar.php" class="nav-link">Explorar</a>
            
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Buscar">
                <button class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
            
            <button class="dropdown-btn">
                Mis Cursos ▼
            </button>
            
            <a href="/portal_cursos/views/courses/cursos.php" class="nav-link">Cursos</a>
            <a href="/portal_cursos/views/courses/talleres.php" class="nav-link">Talleres</a>

            <?php if (isset($_SESSION['user_rol'])): ?>
                <?php if ($_SESSION['user_rol'] === 'instructor'): ?>
                    <a href="/portal_cursos/views/courses/crearCurso.php" class="nav-link">Crear Curso</a>
                <?php elseif ($_SESSION['user_rol'] === 'administrador'): ?>
                    <a href="/portal_cursos/views/courses/gestionCursos.php" class="nav-link">Gestionar Cursos</a>
                    <a href="/portal_cursos/views/admin/gestionUsuario.php" class="nav-link">Gestionar Usuarios</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span style="color: white;">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <a href="/portal_cursos/public/auth_handler.php?action=logout" class="btn btn-secondary">Cerrar Sesión</a>
            <?php else: ?>
                <a href="/portal_cursos/views/auth/registro.php" class="btn btn-primary">Regístrate</a>
                <a href="/portal_cursos/views/auth/login.php" class="btn btn-secondary">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    // Simple script for mobile navigation toggle
    document.addEventListener('DOMContentLoaded', function() {
        const navToggleBtn = document.getElementById('nav-toggle-btn');
        const navContainer = document.getElementById('nav-container');
        const header = document.querySelector('.header');

        if (navToggleBtn && navContainer && header) {
            navToggleBtn.addEventListener('click', function() {
                navContainer.classList.toggle('nav-active');
                header.classList.toggle('menu-open');
            });
        }
    });
</script>

</html>