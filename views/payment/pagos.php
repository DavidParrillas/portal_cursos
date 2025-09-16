<?php include __DIR__ . '/../layouts/layout.php'; ?>

    <!-- Dark Section -->
<section class="hero-section">
    <div class="hero-container">
        <h1 class="hero-title">Pagos</h1>
        <button class="payment-methods-btn">Métodos de pago</button>
    </div>
</section>

<!-- Payment Methods Section -->
<section class="payment-section">
    <div class="payment-container">
        <h2 class="payment-title">Selecciona un método de pago</h2>

        <div class="payment-grid">
            <!-- PayPal Card -->
            <div class="payment-card" onclick="selectPaymentMethod('paypal')">
                <div class="payment-card-content">
                    <div class="payment-icon">
                        <img src="paypal-logo-blue.jpg" alt="PayPal" class="payment-logo">
                    </div>
                    <h3 class="payment-method-name">PayPal</h3>
                </div>
            </div>

            <!-- Credit/Debit Card -->
            <div class="payment-card" onclick="selectPaymentMethod('card')">
                <div class="payment-card-content">
                    <div class="payment-icon">
                        <img src="credit-card-illustration-with-hand-holding-green-c.jpg" alt="Tarjeta de crédito" class="payment-logo">
                    </div>
                    <h3 class="payment-method-name">Tarjeta de crédito o débito</h3>
                </div>
            </div>
        </div>
    </div>
</section>