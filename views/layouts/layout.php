<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Curzilla - Plataforma de Cursos Online'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/portal_cursos/public/assets/css/curzilla.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/portal_cursos/public/assets/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
<header class="header">
    <div class="logo">
        <a href="/portal_cursos/public/index.php">
            <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-g55lksb0cwSrjGGG1q02lKfSWJACu1.png" alt="Curzilla Logo">
            <h1 class="sr-only">Curzilla</h1>
        </a>
    </div>
    <nav class="nav-container" id="nav-container">
        <div class="nav-center">
            <a href="/portal_cursos/views/courses/explorar.php" class="nav-link">Explorar</a>
            
            <form role="search" class="search-container">
                <label for="search" class="sr-only">Buscar</label>
                <input type="search" id="search" class="search-input" placeholder="Buscar">
                <button type="submit" class="search-btn" aria-label="Buscar"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            
            <div class="dropdown">
                <button class="dropdown-btn">
                    Mis Cursos <span aria-hidden="true">▼</span>
                </button>
                <div class="dropdown-content">
                    <a href="/portal_cursos/views/courses/cursos.php">Cursos</a>
                    <a href="/portal_cursos/views/courses/talleres.php">Talleres</a>
                </div>
            </div>

            <?php if (isset($_SESSION['user_rol'])): ?>
                <?php if ($_SESSION['user_rol'] === 'instructor'): ?>
                    <a href="/portal_cursos/views/courses/crearCurso.php" class="nav-link">Crear Curso</a>
                <?php elseif ($_SESSION['user_rol'] === 'administrador'): ?>
                    <a href="/portal_cursos/views/admin/gestionCursos.php" class="nav-link">Gestionar Cursos</a>
                    <a href="/portal_cursos/views/admin/gestionUsuario.php" class="nav-link">Gestionar Usuarios</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <div class="nav-right user-menu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="user-name"><i class="fa-solid fa-circle-user"></i><?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                <a href="/portal_cursos/public/auth_handler.php?action=logout" class="btn btn-secondary">Cerrar Sesión</a>
            <?php else: ?>
                <a href="/portal_cursos/views/auth/registro.php" class="btn btn-primary">Regístrate</a>
                <a href="/portal_cursos/views/auth/login.php" class="btn btn-secondary">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </nav>
    <button class="nav-toggle-btn" id="nav-toggle-btn" aria-label="Toggle navigation">
        <i class="fa-solid fa-bars"></i>
    </button>
</header>

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

        // Dropdown functionality
        const dropdown = document.querySelector('.dropdown');
        if (dropdown) {
            const dropdownBtn = dropdown.querySelector('.dropdown-btn');
            dropdownBtn.addEventListener('click', function() {
                dropdown.classList.toggle('show');
            });
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-btn')) {
                const dropdowns = document.getElementsByClassName("dropdown");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    });
</script>

</html>