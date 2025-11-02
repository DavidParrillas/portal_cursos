<?php 
$pageTitle = "Validación de cupos - Curzilla";
ob_start();
?>

<header class="hero-section">
  <div class="hero-container">
    <h1 class="hero-title">Validación de cupos disponibles</h1>
    <a href="/portal_cursos/views/payment/pagos.php" class="btn btn-outline">← Volver a pagos</a>
  </div>
</header>

<main class="payment-section">
  <div class="payment-container">
    <h2 class="payment-title">Consulta de cupos</h2>

    <section class="card">
      <form class="form-grid" onsubmit="return false;">
        <div class="form-field">
          <label for="cursoId">ID del curso</label>
          <input type="number" id="cursoId" placeholder="Ej. 101" min="1" required>
        </div>

        <div class="form-field">
          <label for="turno">Turno</label>
          <select id="turno">
            <option value="manana">Mañana</option>
            <option value="tarde">Tarde</option>
            <option value="noche">Noche</option>
          </select>
        </div>

        <div class="form-actions">
          <button class="btn btn-primary btn-lg" onclick="checkCupos()">Validar cupos</button>
          <button class="btn btn-ghost" type="reset" onclick="resetResultados()">Limpiar</button>
        </div>
      </form>

      <div id="resultado" class="result hidden">
        <div class="result-header">
          <div>
            <div class="result-title">Resultado de validación</div>
            <div class="result-sub">Curso <span id="r-curso"></span> • Turno <span id="r-turno"></span></div>
          </div>
          <span id="r-badge" class="badge">—</span>
        </div>

        <div class="kpis">
          <div class="kpi">
            <div class="kpi-label">Capacidad</div>
            <div class="kpi-value" id="r-capacidad">—</div>
          </div>
          <div class="kpi">
            <div class="kpi-label">Inscritos</div>
            <div class="kpi-value" id="r-inscritos">—</div>
          </div>
          <div class="kpi">
            <div class="kpi-label">Disponibles</div>
            <div class="kpi-value" id="r-disponibles">—</div>
          </div>
        </div>

        <div class="progress-wrap">
          <div class="progress-bar">
            <div id="r-progress" class="progress-fill" style="width:0%"></div>
          </div>
          <div class="progress-legend"><span id="r-percent">0%</span> ocupado</div>
        </div>

        <div class="result-actions">
          <a href="/portal_cursos/controllers/InstructorController.php?action=goConfirmacion" 
             class="btn btn-success btn-lg" id="r-continuar" aria-disabled="true">Continuar a inscripción</a>
        </div>
      </div>
    </section>
  </div>
</main>

<style>
/* Card base */
.card{
  background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:1rem;
  box-shadow:0 8px 24px rgba(0,0,0,.08);padding:1.25rem;max-width:900px;margin:0 auto 2rem;
}

/* Form */
.form-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;align-items:end}
.form-field label{display:block;font-weight:700;margin-bottom:.4rem}
.form-field input,.form-field select{
  width:100%;padding:.75rem;border:1px solid #e5e7eb;border-radius:.6rem;font-size:1rem
}
.form-actions{display:flex;gap:.75rem;align-items:center}

/* Result */
.hidden{display:none}
.result{margin-top:1rem}
.result-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
.result-title{font-size:1.05rem;font-weight:800}
.result-sub{color:#64748b;font-size:.95rem;margin-top:.15rem}

.kpis{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin:1rem 0}
.kpi{background:#f8fafc;border:1px solid #eef2f7;border-radius:.8rem;padding:1rem}
.kpi-label{color:#6b7280;font-size:.85rem}
.kpi-value{font-weight:800;font-size:1.35rem;margin-top:.25rem}

.progress-wrap{margin-top:.5rem}
.progress-bar{height:10px;background:#f1f5f9;border-radius:999px;overflow:hidden}
.progress-fill{height:100%;background:#7c3aed}
.progress-legend{margin-top:.4rem;color:#64748b;font-size:.9rem}

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

/* Badge estados */
.badge{padding:.35rem .6rem;border-radius:999px;font-weight:700;font-size:.85rem}
.badge.ok{background:#dcfce7;color:#166534}
.badge.warn{background:#fef9c3;color:#854d0e}
.badge.err{background:#fee2e2;color:#991b1b}

@media (max-width: 840px){
  .form-grid{grid-template-columns:1fr}
  .kpis{grid-template-columns:1fr}
}
</style>

<script>
const BASE = '/portal_cursos';

// cambiar USE_API a true y setear la URL:
const USE_API = false;
// const API_URL = `${BASE}/controllers/InstructorController.php?action=checkCupos`;

function resetResultados(){
  document.getElementById('resultado').classList.add('hidden');
}

async function checkCupos(){
  const cursoId = document.getElementById('cursoId').value.trim();
  const turno = document.getElementById('turno').value;
  if(!cursoId){ return; }

  let payload;

  if (USE_API) {
    const res = await fetch(API_URL, {
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify({ cursoId, turno })
    });
    payload = await res.json(); 
  } else {
    const capacidad = 30;                     
    const inscritos = Math.floor(Math.random()*35);
    payload = { capacidad, inscritos };
  }

  renderResultados(cursoId, turno, payload);
}

function renderResultados(cursoId, turno, {capacidad, inscritos}){
  const disponibles = Math.max(capacidad - inscritos, 0);
  const percent = Math.min(Math.round((inscritos / capacidad) * 100), 100);

  document.getElementById('r-curso').innerText = cursoId;
  document.getElementById('r-turno').innerText = turno;

  document.getElementById('r-capacidad').innerText = capacidad;
  document.getElementById('r-inscritos').innerText = inscritos;
  document.getElementById('r-disponibles').innerText = disponibles;

  document.getElementById('r-progress').style.width = percent + '%';
  document.getElementById('r-percent').innerText = percent + '%';

  const badge = document.getElementById('r-badge');
  badge.classList.remove('ok','warn','err');
  let state = 'ok', text = 'Disponible';
  if (disponibles <= 0) { state='err'; text='Lleno'; }
  else if (percent >= 80) { state='warn'; text='Casi lleno'; }
  badge.classList.add(state);
  badge.innerText = text;

  const continuar = document.getElementById('r-continuar');
  if (disponibles > 0){
    continuar.setAttribute('aria-disabled','false');
  } else {
    continuar.setAttribute('aria-disabled','true');
  }

  document.getElementById('resultado').classList.remove('hidden');
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>
