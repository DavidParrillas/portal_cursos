<?php 
$pageTitle = "Confirmación de inscripción - Curzilla";

/* Si $curso no viene del controlador, usa defaults/GET */
if (!isset($curso) || !is_array($curso)) {
    $curso = [
        'titulo' => $_GET['titulo'] ?? 'Curso de Ejemplo',
        'inicio' => $_GET['inicio'] ?? date('Y-m-d'),
        'precio' => $_GET['precio'] ?? '49.99',
        'moneda' => $_GET['moneda'] ?? 'USD',
    ];
}

/* Cálculos compra */
$qty        = 1;
$precio     = (float) $curso['precio'];
$subtotal   = $precio * $qty;
$ivaRate    = 0.13;              // IVA 13%
$iva        = round($subtotal * $ivaRate, 2);
$total      = round($subtotal + $iva, 2);
$orderId    = strtoupper(dechex(time())); // ID visual
ob_start();
?>

<header class="hero-section">
  <div class="hero-container">
    <h1 class="hero-title">Confirmación de inscripción</h1>
    <a href="/portal_cursos/views/payment/pagos.php" class="btn btn-outline">
      ← Volver a pagos
    </a>
  </div>
</header>

<main class="payment-section">
  <div class="payment-container">

    <h2 class="payment-title">Resumen de la orden</h2>

    <section class="order-card">
      <div class="order-header">
        <div>
          <div class="order-code">Orden #<?= htmlspecialchars($orderId) ?></div>
          <div class="order-date">Fecha: <?= date('Y-m-d') ?></div>
        </div>
        <div class="order-badge">Inscripción</div>
      </div>

      <div class="order-body">
        <!-- Datos del alumno/curso (si quisieras, aquí puedes poner nombre del alumno desde sesión) -->
        <div class="order-meta">
          <div>
            <div class="label">Curso</div>
            <div class="value"><?= htmlspecialchars($curso['titulo']) ?></div>
          </div>
          <div>
            <div class="label">Fecha de inicio</div>
            <div class="value"><?= htmlspecialchars($curso['inicio']) ?></div>
          </div>
          <div>
            <div class="label">Moneda</div>
            <div class="value"><?= htmlspecialchars($curso['moneda']) ?></div>
          </div>
        </div>

        <!-- Tabla de ítems -->
        <div class="order-table-wrap">
          <table class="order-table">
            <thead>
              <tr>
                <th>Ítem</th>
                <th class="th-center">Cant.</th>
                <th class="th-right">Precio</th>
                <th class="th-right">Importe</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="item-title"><?= htmlspecialchars($curso['titulo']) ?></div>
                  <div class="item-sub">Acceso al curso y materiales</div>
                </td>
                <td class="td-center"><?= $qty ?></td>
                <td class="td-right">$<?= number_format($precio, 2) ?></td>
                <td class="td-right">$<?= number_format($subtotal, 2) ?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Totales -->
        <div class="totals">
          <div class="totals-row">
            <span>Subtotal</span>
            <strong>$<?= number_format($subtotal, 2) ?></strong>
          </div>
          <div class="totals-row">
            <span>IVA (13%)</span>
            <strong>$<?= number_format($iva, 2) ?></strong>
          </div>
          <div class="totals-row totals-row-total">
            <span>Total</span>
            <strong>$<?= number_format($total, 2) ?> <?= htmlspecialchars($curso['moneda']) ?></strong>
          </div>
        </div>

        <!-- Acciones -->
        <form
          action="/portal_cursos/controllers/InstructorController.php?action=confirmarInscripcion"
          method="POST" class="order-actions">
          <input type="hidden" name="curso_titulo" value="<?= htmlspecialchars($curso['titulo']) ?>">
          <input type="hidden" name="curso_inicio" value="<?= htmlspecialchars($curso['inicio']) ?>">
          <input type="hidden" name="precio" value="<?= number_format($precio, 2, '.', '') ?>">
          <input type="hidden" name="total" value="<?= number_format($total, 2, '.', '') ?>">
          <button type="submit" class="btn btn-primary btn-lg">
            Confirmar inscripción
          </button>
          <a href="/portal_cursos/views/payment/pagos.php" class="btn btn-ghost">
            Cancelar
          </a>
        </form>
      </div>
    </section>

  </div>
</main>

<style>
/* ---- Estilos “orden de compra” (scoped a esta vista) ---- */
.order-card{
  background:#fff;
  border-radius:1rem;
  box-shadow:0 8px 24px rgba(0,0,0,.08);
  border:1px solid rgba(0,0,0,.06);
  overflow:hidden;
  max-width:900px;
  margin:0 auto 2rem;
}
.order-header{
  display:flex;align-items:center;justify-content:space-between;
  padding:1rem 1.25rem;
  background:linear-gradient(90deg,#f5f7ff,#f9fbff);
  border-bottom:1px solid rgba(0,0,0,.06);
}
.order-code{font-weight:700;font-size:1.05rem}
.order-date{font-size:.9rem;color:#667085}
.order-badge{
  background:#eef2ff;color:#4338ca;
  padding:.35rem .65rem;border-radius:999px;font-weight:600;font-size:.85rem
}
.order-body{padding:1.25rem}

.order-meta{
  display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1rem
}
.order-meta .label{font-size:.8rem;color:#6b7280}
.order-meta .value{font-weight:600}

.order-table-wrap{overflow:auto;border-radius:.75rem;border:1px solid #e5e7eb}
.order-table{width:100%;border-collapse:separate;border-spacing:0}
.order-table thead th{
  text-align:left;background:#fafafa;border-bottom:1px solid #e5e7eb;
  padding:.85rem .9rem;font-size:.9rem;color:#374151
}
.order-table td{
  padding:1rem .9rem;border-bottom:1px solid #f1f5f9;vertical-align:top
}
.item-title{font-weight:600}
.item-sub{font-size:.85rem;color:#6b7280;margin-top:.15rem}
.th-center,.td-center{text-align:center}
.th-right,.td-right{text-align:right}

.totals{max-width:380px;margin:1rem 0 0 auto}
.totals-row{
  display:flex;justify-content:space-between;align-items:center;
  padding:.5rem 0;border-bottom:1px dashed #e5e7eb
}
.totals-row-total{border-bottom:none;font-size:1.1rem}
.totals-row-total strong{font-size:1.15rem}

.order-actions{
  display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.25rem;flex-wrap:wrap
}

/* Botones bonitos */
.btn{display:inline-flex;align-items:center;justify-content:center;
  border:1px solid transparent;border-radius:.75rem;
  padding:.7rem 1rem;font-weight:700;cursor:pointer;text-decoration:none;
  transition:.2s ease-in-out;font-size:.95rem
}
.btn-lg{padding:.9rem 1.25rem;font-size:1rem;border-radius:.85rem}
.btn-primary{background:#7c3aed;color:#fff;border-color:#6d28d9;box-shadow:0 4px 14px rgba(124,58,237,.25)}
.btn-primary:hover{background:#6d28d9}
.btn-outline{background:#fff;border-color:#e5e7eb;color:#111827}
.btn-outline:hover{background:#f9fafb}
.btn-ghost{background:transparent;color:#6b7280}
.btn-ghost:hover{background:#f3f4f6;color:#111827}

@media (max-width: 840px){
  .order-meta{grid-template-columns:1fr}
  .totals{max-width:100%}
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
