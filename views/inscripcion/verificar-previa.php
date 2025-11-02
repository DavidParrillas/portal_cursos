<?php 
$pageTitle = "Verificación de inscripción previa - Curzilla";
ob_start();
?>

<header class="hero-section">
  <div class="hero-container">
    <h1 class="hero-title">Verificación de inscripción previa</h1>
    <a href="/portal_cursos/views/payment/pagos.php" class="btn btn-outline">← Volver a pagos</a>
  </div>
</header>

<main class="payment-section">
  <div class="payment-container">
    <h2 class="payment-title">Busca tu inscripción</h2>

    <section class="card">
      <form class="form-grid" onsubmit="return false;">
        <div class="form-field">
          <label for="email">Correo del estudiante</label>
          <input type="email" id="email" placeholder="ejemplo@correo.com" required>
        </div>

        <div class="form-field">
          <label for="cursoId">ID del curso</label>
          <input type="number" id="cursoId" placeholder="Ej. 101" min="1" required>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-primary btn-lg" onclick="verificarInscripcion()">Verificar</button>
          <button type="button" class="btn btn-ghost" onclick="resetResultado()">Limpiar</button>
        </div>
      </form>

      <div id="resultado" class="result hidden">
        <div class="result-header">
          <div>
            <div class="result-title">Resultado</div>
            <div class="result-sub">
              <span id="r-email">—</span> • Curso <span id="r-curso">—</span>
            </div>
          </div>
          <span id="r-badge" class="badge">—</span>
        </div>

        <div class="result-grid">
          <div class="result-box">
            <div class="label">Estado</div>
            <div id="r-estado" class="value">—</div>
          </div>
          <div class="result-box">
            <div class="label">Fecha de inscripción</div>
            <div id="r-fecha" class="value">—</div>
          </div>
          <div class="result-box">
            <div class="label">Referencia</div>
            <div id="r-ref" class="value">—</div>
          </div>
        </div>

        <div class="result-actions">
          <a id="cta-primaria" href="#" class="btn btn-success btn-lg" aria-disabled="true">Continuar</a>
          <a href="/portal_cursos/views/payment/pagos.php" class="btn btn-ghost">Volver a pagos</a>
        </div>
      </div>
    </section>
  </div>
</main>

<style>
.card{
  background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:1rem;
  box-shadow:0 8px 24px rgba(0,0,0,.08);padding:1.25rem;max-width:900px;margin:0 auto 2rem;
}
.form-grid{display:grid;grid-template-columns:2fr 1fr auto;gap:1rem;align-items:end}
.form-field label{display:block;font-weight:700;margin-bottom:.4rem}
.form-field input{
  width:100%;padding:.75rem;border:1px solid #e5e7eb;border-radius:.6rem;font-size:1rem
}
.form-actions{display:flex;gap:.75rem;align-items:center}
.hidden{display:none}
.result{margin-top:1rem}
.result-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
.result-title{font-size:1.05rem;font-weight:800}
.result-sub{color:#64748b;font-size:.95rem;margin-top:.15rem}
.result-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin:1rem 0}
.result-box{background:#f8fafc;border:1px solid #eef2f7;border-radius:.8rem;padding:1rem}
.label{color:#6b7280;font-size:.85rem}
.value{font-weight:800;font-size:1.05rem;margin-top:.2rem}
.result-actions{display:flex;gap:.75rem;justify-content:flex-end;flex-wrap:wrap;margin-top:.75rem}

/* Buttons */
.btn{display:inline-flex;align-items:center;justify-content:center;
  border:1px solid transparent;border-radius:.75rem;padding:.7rem 1rem;
  font-weight:700;cursor:pointer;text-decoration:none;transition:.2s ease;font-size:.95rem}
.btn-lg{padding:.9rem 1.25rem;border-radius:.85rem}
.btn-primary{background:#7c3aed;color:#fff;border-color:#6d28d9;box-shadow:0 4px 14px rgba(124,58,237,.25)}
.btn-primary:hover{background:#6d28d9}
.btn-outline{background:#fff;border-color:#e5e7eb;color:#111827}
.btn-outline:hover{background:#f9fafb}
.btn-ghost{background:transparent;color:#6b7280}
.btn-ghost:hover{background:#f3f4f6;color:#111827}
.btn-success{background:#16a34a;color:#fff}
.btn-success[aria-disabled="true"]{opacity:.5;pointer-events:none}

/* Badge */
.badge{padding:.35rem .6rem;border-radius:999px;font-weight:700;font-size:.85rem}
.badge.ok{background:#dcfce7;color:#166534}
.badge.err{background:#fee2e2;color:#991b1b}

@media (max-width: 900px){
  .form-grid{grid-template-columns:1fr}
  .result-grid{grid-template-columns:1fr}
}
</style>

<script>
const BASE = '/portal_cursos';
const USE_API = false; 
function resetResultado(){
  document.getElementById('resultado').classList.add('hidden');
}

async function verificarInscripcion(){
  const email = document.getElementById('email').value.trim();
  const cursoId = document.getElementById('cursoId').value.trim();
  if(!email || !cursoId){ return; }

  let payload;
  if (USE_API) {
    const res = await fetch(API_URL, {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ email, cursoId })
    });
    payload = await res.json(); 
  } else {
    const existe = Math.random() > 0.5;
    const fecha = existe ? new Date(Date.now()-86400000*3).toISOString().slice(0,10) : null;
    const ref   = existe ? ('ENR-' + Math.random().toString(36).slice(2,8).toUpperCase()) : null;
    payload = { existe, fecha_iso: fecha, referencia: ref };
  }

  renderResultado({ email, cursoId, ...payload });
}

function renderResultado({email, cursoId, existe, fecha_iso, referencia}){
  document.getElementById('r-email').innerText = email;
  document.getElementById('r-curso').innerText = cursoId;

  const estado = document.getElementById('r-estado');
  const fecha  = document.getElementById('r-fecha');
  const ref    = document.getElementById('r-ref');
  const badge  = document.getElementById('r-badge');
  const cta    = document.getElementById('cta-primaria');

  badge.classList.remove('ok','err');

  if (existe) {
    estado.innerText = 'Inscrito';
    fecha.innerText  = fecha_iso || '—';
    ref.innerText    = referencia || '—';
    badge.classList.add('ok');
    badge.innerText  = 'Inscrito';

    cta.href = `${BASE}/views/cursos/detalle.php?id=${encodeURIComponent(cursoId)}`;
    cta.textContent = 'Ir al curso';
    cta.setAttribute('aria-disabled','false');
  } else {
    estado.innerText = 'No inscrito';
    fecha.innerText  = '—';
    ref.innerText    = '—';
    badge.classList.add('err');
    badge.innerText  = 'No inscrito';

    cta.href = `${BASE}/controllers/InstructorController.php?action=goConfirmacion&titulo=Curso%20${encodeURIComponent(cursoId)}&inicio=${new Date().toISOString().slice(0,10)}&precio=49.99`;
    cta.textContent = 'Inscribirme';
    cta.setAttribute('aria-disabled','false');
  }

  document.getElementById('resultado').classList.remove('hidden');
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
