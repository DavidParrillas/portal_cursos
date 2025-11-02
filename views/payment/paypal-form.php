<?php 
$pageTitle = "Pago con PayPal - Curzilla";
ob_start();
?>

<header class="hero-section">
    <div class="hero-container">
        <h1 class="hero-title">Pago con PayPal</h1>
        <a href="/portal_cursos/views/payment/pagos.php" class="payment-methods-btn">
        ← Volver a métodos de pago
        </a>
    </div>
</header>

<main id="paypal-form" class="payment-section">
    <div class="payment-container">
        <h2 class="payment-title">Completa los datos para tu pago</h2>

        <form action="/controllers/PaymentController.php?action=procesarPagoPaypal" method="POST" class="payment-form">
            <div class="form-group">
                <label for="name">Nombre completo</label>
                <input type="text" id="name" name="name" required placeholder="Ej. Ricardo Amílcar">
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico (cuenta PayPal)</label>
                <input type="email" id="email" name="email" required placeholder="ejemplo@correo.com">
            </div>

            <div class="form-group">
                <label for="amount">Monto a pagar (USD)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="1" required placeholder="Ej. 25.00">
            </div>

            <div class="form-group">
                <label for="description">Descripción del pago</label>
                <textarea id="description" name="description" rows="3" placeholder="Ej. Curso de Desarrollo Web"></textarea>
            </div>

            <div class="payment-summary">
                <div class="payment-icon">
                    <img src="/assets/img/placeholders/paypal-logo-blue.jpg" 
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
