<?php 
$pageTitle = "Registro - Curzilla";
include __DIR__ . '/../layouts/layout.php';
?>

<div class="main-content">
    <!-- Illustration Section -->
    <div class="illustration-section">
        <img src="/portal_cursos/public/assets/img/placeholders/undraw_explore_kfv3%201.png" class="illustration-image">
    </div>

    <!-- Registration Form Section -->
    <div class="form-section">
        <div class="form-container">
            <h1 class="form-title">Crea tu cuenta</h1>
            <p class="form-subtitle">Únete a nuestra comunidad y empieza a aprender</p>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <p style="color: red; text-align: center; margin-bottom: 1rem;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="/portal_cursos/public/auth_handler.php">
                <input type="hidden" name="action" value="register">
                
                <div class="form-group">
                    <input type="text" name="nombre" class="form-input" placeholder="Nombre completo" required>
                </div>

                <div class="form-group">
                    <input type="email" name="correo" class="form-input" placeholder="Correo electrónico" required>
                </div>
                
                <div class="form-group">
                    <div class="input-container">
                        <input type="password" id="password" name="contrasena" class="form-input" placeholder="Contraseña" required>
                        <button type="button" class="toggle-password" data-target="password">
                            <i class="fa-regular fa-eye" style="color: #000000;"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="rol" class="form-label">Quiero registrarme como:</label>
                    <select name="rol" id="rol" class="form-select">
                        <option value="estudiante" selected>Estudiante</option>
                        <option value="instructor">Instructor</option>
                    </select>
                </div>
                
                <button type="submit" class="register-btn">Crear Cuenta</button>
                
                <div class="login-link">
                    ¿Ya tienes una cuenta? <a href="/portal_cursos/views/auth/login.php">Inicia sesión aquí</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelector('.toggle-password').addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        if (input.type === 'password') {
            input.type = 'text';
            this.innerHTML = '<i class="fa-regular fa-eye-slash" style="color: #000000;"></i>';
        } else {
            input.type = 'password';
            this.innerHTML = '<i class="fa-regular fa-eye" style="color: #000000;"></i>';
        }
    });
</script>