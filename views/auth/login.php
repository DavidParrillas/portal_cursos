<?php 
$pageTitle = "Iniciar Sesión - Curzilla";
include __DIR__ . '/../layouts/layout.php';
?>

<div class="main-content">
    <!-- Illustration Section -->
    <div class="illustration-section">
        <img src="/portal_cursos/public/assets/img/placeholders/undraw_explore_kfv3%201.png" class="illustration-image">
    </div>

    <!-- Login Form Section -->
    <div class="form-section">
        <div class="form-container">
            <h1 class="form-title">Inicia sesión en tu cuenta</h1>
            <p class="form-subtitle">Accede a tus cursos y contenido personalizado</p>

            <?php if (isset($_SESSION['error_message'])): ?>
                <p style="color: red; text-align: center; margin-bottom: 1rem;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="/portal_cursos/public/auth_handler.php">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <input type="email" name="correo" class="form-input" placeholder="Correo electrónico" required>
                </div>
                
                <div class="form-group">
                    <div class="input-container">
                        <input 
                            type="password" 
                            id="password"
                            name="contrasena"
                            class="form-input" 
                            placeholder="Contraseña"
                            required
                        >
                        <button type="button" class="toggle-password" data-target="password">
                            <i class="fa-regular fa-eye" style="color: #000000;"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="continue-btn">Iniciar Sesión</button>
                
                <div class="help-link">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>
                
                <div class="login-link">
                    ¿No tienes una cuenta? <a href="/portal_cursos/views/auth/registro.php">Regístrate aquí</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
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