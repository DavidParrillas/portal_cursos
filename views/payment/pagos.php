<?php 
$pageTitle = "Pagos - Curzilla";
ob_start();
?>

<header class="hero-section">
    <div class="hero-container">
        <h1 class="hero-title">Pagos</h1>
        <a href="#payment-methods" class="payment-methods-btn">Métodos de pago</a>
    </div>
</header>

<main id="payment-methods" class="payment-section">
    <div class="payment-container">
        <h2 class="payment-title">Selecciona un método de pago</h2>

        <section class="payment-grid">
            <article class="payment-card">
                <button class="payment-card-content" onclick="selectPaymentMethod('paypal')">
                    <div class="payment-icon">
                        <img src="/portal_cursos/public/assets/img/placeholders/PayPal.svg.png" alt="PayPal" class="payment-logo">
                    </div>
                    <h3 class="payment-method-name">PayPal</h3>
                </button>
            </article>

            <article class="payment-card">
                <button class="payment-card-content" onclick="selectPaymentMethod('card')">
                    <div class="payment-icon">
                        <img src="/portal_cursos/public/assets/img/placeholders/png-transparent-credit-card-icon-money-credit-card-finance-credit-card-icon-shopping-bank-buy.png" alt="Tarjeta de crédito" class="payment-logo">
                    </div>
                    <h3 class="payment-method-name">Tarjeta de crédito o débito</h3>
                </button>
            </article>
        </section>
    </div>
</main>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>

<script>
const BASE = '/portal_cursos';

function selectPaymentMethod(method) {
  const routes = {
    paypal: `${BASE}/views/payment/paypal-form.php`,
    card:   `${BASE}/views/payment/card-form.php` // si luego agregas tarjeta
  };

  const url = routes[method];
  if (!url) {
    alert('Método de pago no reconocido');
    return;
  }

  // Redirección entre folders
  window.location.assign(url);
}
</script>
