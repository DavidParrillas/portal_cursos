<?php 
$pageTitle = "Inscripción - Curzilla";
ob_start();
?>

<header class="hero-section">
  <div class="hero-container">
    <h1 class="hero-title">Inscripción</h1>
    <a href="/portal_cursos/views/payment/pagos.php" class="btn btn-outline">← Volver a pagos</a>
  </div>
</header>

<main class="payment-section">
  <div class="payment-container">
    <h2 class="payment-title">Acciones</h2>

    <section class="action-grid">
      <!-- Confirmación -->
      <a class="action-card" href="/portal_cursos/controllers/InstructorController.php?action=goConfirmacion">
        <div class="action-icon">🧾</div>
        <div class="action-body">
          <h3 class="action-title">Confirmación de inscripción</h3>
          <p class="action-sub">Resumen tipo orden y confirmación del cupo.</p>
        </div>
        <span class="action-chev">→</span>
      </a>

      <!-- Validar cupos -->
      <a class="action-card" href="/portal_cursos/controllers/InstructorController.php?action=goValidarCupos">
        <div class="action-icon">📊</div>
        <div class="action-body">
          <h3 class="action-title">Validación de cupos disponibles</h3>
          <p class="action-sub">Capacidad, inscritos, disponibles y estado.</p>
        </div>
        <span class="action-chev">→</span>
      </a>

      <!-- Verificar previa -->
      <a class="action-card" href="/portal_cursos/controllers/InstructorController.php?action=goVerificarPrevia">
        <div class="action-icon">🔎</div>
        <div class="action-body">
          <h3 class="action-title">Verificación de inscripción previa</h3>
          <p class="action-sub">Comprueba si el estudiante ya está inscrito.</p>
        </div>
        <span class="action-chev">→</span>
      </a>
    </section>
  </div>
</main>

<style>
.action-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:1.25rem;
  margin-top:.5rem;
}

.action-card{
  display:grid; grid-template-columns:auto 1fr auto; gap:1rem;
  align-items:center; text-decoration:none;
  background:#fff; border:1px solid rgba(0,0,0,.06); border-radius:1rem;
  padding:1rem 1.1rem; box-shadow:0 8px 24px rgba(0,0,0,.08);
  transition:transform .15s ease, box-shadow .15s ease, border-color .15s ease;
}
.action-card:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 28px rgba(0,0,0,.12);
  border-color:#e5e7eb;
}
.action-icon{
  width:44px;height:44px;border-radius:.9rem;
  display:flex;align-items:center;justify-content:center;
  font-size:1.35rem;background:#f5f3ff;color:#6d28d9;font-weight:700;
}
.action-title{margin:0;font-size:1.05rem;font-weight:800;color:#111827}
.action-sub{margin:.2rem 0 0 0;color:#6b7280;font-size:.92rem}
.action-chev{color:#9ca3af;font-weight:800}

@media (max-width: 1024px){ .action-grid{grid-template-columns:1fr 1fr} }
@media (max-width: 640px){ .action-grid{grid-template-columns:1fr} }

.btn{display:inline-flex;align-items:center;justify-content:center;
  border:1px solid transparent;border-radius:.75rem;padding:.7rem 1rem;
  font-weight:700;cursor:pointer;text-decoration:none;transition:.2s ease;font-size:.95rem}
.btn-outline{background:#fff;border-color:#e5e7eb;color:#111827}
.btn-outline:hover{background:#f9fafb}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
