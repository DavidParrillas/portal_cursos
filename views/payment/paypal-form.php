<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Pago con PayPal - Curzilla";
ob_start();

$curso_a_pagar = $_SESSION['current_course_payment'] ?? null;
$sessionUserId = $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? null;

if (!$curso_a_pagar || !$sessionUserId) {
    // Redirigir si no hay datos de curso para pagar o usuario no logueado
    $_SESSION['mensaje'] = 'No se encontró información del curso o usuario para procesar el pago.';
    $_SESSION['mensaje_tipo'] = 'danger';
    header('Location: /portal_cursos/public/index.php');
    exit;
}

$idCurso = $curso_a_pagar['id_curso'];
$idUsuario = $sessionUserId;
$precioCurso = $curso_a_pagar['precio'];
$tituloCurso = $curso_a_pagar['titulo'];

// Puedes obtener el nombre y correo del usuario de la sesión si están disponibles
$nombreUsuario = $_SESSION['user_nombre'] ?? '';
$correoUsuario = $_SESSION['user_email'] ?? ''; // Asumiendo que el correo está en la sesión

?>

<header class="hero-section">
    <div class="hero-container">
        <h1 class="hero-title">Pago con PayPal</h1>
    </div>
</header>

<main id="paypal-form" class="payment-section">
    <div class="payment-container">
        <h2 class="payment-title">Completa los datos para tu pago</h2>

        <form action="/portal_cursos/controllers/PagoController.php?action=procesarPagoPaypal" method="POST" class="payment-form">
            <input type="hidden" name="id_curso" value="<?= htmlspecialchars($idCurso) ?>">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($idUsuario) ?>">

            <div class="form-group">
                <label for="name">Nombre completo</label>
                <input type="text" id="name" name="name" required placeholder="Ej. Ricardo Amílcar" value="<?= htmlspecialchars($nombreUsuario) ?>">
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico (cuenta PayPal)</label>
                <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com" value="<?= htmlspecialchars($correoUsuario) ?>">
            </div>

            <div class="form-group">
                <label for="amount">Monto a pagar (USD)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="1" required value="<?= htmlspecialchars(number_format($precioCurso, 2, '.', '')) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="description">Descripción del pago</label>
                <textarea id="description" name="description" rows="3" readonly><?= htmlspecialchars('Pago por el curso: ' . $tituloCurso) ?></textarea>
            </div>

            <div class="payment-summary">
                <div class="payment-icon">
                    <img src="/portal_cursos/public/assets/img/placeholders/paypal-logo-blue.jpg" 
                         alt="PayPal" class="payment-logo">
                </div>
                <button type="submit" class="payment-submit-btn">
                    Pagar con PayPal
                </button>
            </div>
        </form>
    </div>
</main>

<style>
.payment-form {
    max-width: 500px;
    margin: 0 auto;
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.form-group {
    margin-bottom: 1rem;
}
.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 0.5rem;
}
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid #ccc;
    font-size: 1rem;
}
.payment-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.5rem;
}
.payment-logo {
    max-width: 100px;
    height: auto;
}
.payment-submit-btn {
    background-color: #0070ba;
    color: white;
    font-weight: bold;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.payment-submit-btn:hover {
    background-color: #005c9d;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
