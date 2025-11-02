<?php 
$pageTitle = "Crea una contraseña segura - Curzilla";
ob_start();
?>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Illustration Section -->
        <aside class="illustration-section">
            <img src="/portal_cursos/public/assets/img/placeholders/undraw_monitor_ypga%201.png" alt="Illustration of a person in front of a monitor." class="illustration-image">
        </aside>

        <!-- Registration Form Section -->
        <section class="form-section">
            <div class="form-container">
                <h1 class="form-title">Crea una contraseña segura</h1>
                <p class="form-subtitle">Crea una contraseña con letras, símbolos y números</p>
                
                <form id="passwordForm">
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-container">
                            <input 
                                type="password" 
                                id="password" 
                                class="form-input" 
                                placeholder="ej: mario123%"
                                required
                                aria-describedby="passwordStrength"
                            >
                            <button type="button" class="toggle-password" data-target="password" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye" style="color: #000000;"></i>
                            </button>
                        </div>
                        <div id="passwordStrength" class="password-strength" aria-live="polite"></div>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                        <div class="input-container">
                            <input 
                                type="password" 
                                id="confirmPassword" 
                                class="form-input" 
                                placeholder="ej: mario123%"
                                required
                                aria-describedby="confirmMessage"
                            >
                            <button type="button" class="toggle-password" data-target="confirmPassword" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye" style="color: #000000;"></i>
                            </button>
                        </div>
                        <div id="confirmMessage" class="password-strength" aria-live="polite"></div>
                    </div>

                    <button type="submit" class="register-btn">Registrar</button>
                </form>

                <p class="help-link">
                    <a href="#" id="helpLink">¿Necesitas ayuda?</a>
                </p>
            </div>
        </section>
    </main>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
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
        });

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];

            if (password.length >= 8) strength++;
            else feedback.push('mínimo 8 caracteres');

            if (/[a-z]/.test(password)) strength++;
            else feedback.push('letras minúsculas');

            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('letras mayúsculas');

            if (/[0-9]/.test(password)) strength++;
            else feedback.push('números');

            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('símbolos');

            return { strength, feedback };
        }

        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.textContent = '';
                return;
            }

            const { strength, feedback } = checkPasswordStrength(password);
            
            if (strength < 3) {
                strengthDiv.textContent = `Débil - Necesita: ${feedback.join(', ')}`;
                strengthDiv.className = 'password-strength strength-weak';
            } else if (strength < 5) {
                strengthDiv.textContent = 'Media - Considera agregar más variedad';
                strengthDiv.className = 'password-strength strength-medium';
            } else {
                strengthDiv.textContent = '¡Fuerte! Excelente contraseña';
                strengthDiv.className = 'password-strength strength-strong';
            }
        });

        // Confirm password validation
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const confirmDiv = document.getElementById('confirmMessage');
            
            if (confirmPassword.length === 0) {
                confirmDiv.textContent = '';
                return;
            }

            if (password === confirmPassword) {
                confirmDiv.textContent = '✓ Las contraseñas coinciden';
                confirmDiv.className = 'password-strength strength-strong';
            } else {
                confirmDiv.textContent = '✗ Las contraseñas no coinciden';
                confirmDiv.className = 'password-strength strength-weak';
            }
        });

        // Form submission
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                return;
            }

            const { strength } = checkPasswordStrength(password);
            if (strength < 3) {
                alert('Por favor, crea una contraseña más fuerte');
                return;
            }

            alert('¡Contraseña creada exitosamente!');
        });

        // Help link
        document.getElementById('helpLink').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Para crear una contraseña segura:\n\n• Usa al menos 8 caracteres\n• Incluye letras mayúsculas y minúsculas\n• Agrega números\n• Usa símbolos especiales (!@#$%^&*)\n• Evita información personal\n• No uses contraseñas comunes');
        });
    </script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
</body>