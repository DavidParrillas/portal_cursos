<?php
require_once __DIR__.'/lib/db.php'; require_once __DIR__.'/lib/auth.php';
$usuarioId = currentUserId(); $cursoId = (int)($_GET['curso_id'] ?? 1); $claseId = (int)($_GET['clase_id'] ?? 0);
$chk = $db->prepare("SELECT 1 FROM inscripciones WHERE usuario_id=? AND curso_id=?"); $chk->execute([$usuarioId,$cursoId]);
if (!$chk->fetch()) { http_response_code(403); die('<p>Acceso restringido. Inscríbete en el curso.</p>'); }
if ($claseId){ $sql = $db->prepare("SELECT c.*, s.curso_id FROM clases c JOIN secciones s ON s.id=c.seccion_id WHERE c.id=?"); $sql->execute([$claseId]); $clase = $sql->fetch(PDO::FETCH_ASSOC); }
if (empty($clase) || (int)$clase['curso_id'] !== $cursoId){ $first = $db->prepare("SELECT c.id FROM clases c JOIN secciones s ON s.id=c.seccion_id WHERE s.curso_id=? ORDER BY s.orden,c.orden,c.id LIMIT 1"); $first->execute([$cursoId]); $claseId = (int)$first->fetchColumn(); header("Location: /aula_virtual/aula_virtual.php?curso_id=$cursoId&clase_id=$claseId"); exit; }
$vec = $db->prepare("SELECT c2.id FROM clases c2 JOIN secciones s2 ON s2.id=c2.seccion_id WHERE s2.curso_id=? ORDER BY s2.orden,c2.orden,c2.id"); $vec->execute([$cursoId]); $ids = array_map('intval',$vec->fetchAll(PDO::FETCH_COLUMN)); $pos = array_search((int)$claseId, $ids); $prev = $pos>0 ? $ids[$pos-1] : null; $next = ($pos !== false && $pos < count($ids)-1) ? $ids[$pos+1] : null;
$prog = $db->prepare("SELECT completada FROM progreso_clase WHERE usuario_id=? AND clase_id=?"); $prog->execute([$usuarioId,$claseId]); $done = (int)($prog->fetchColumn() ?: 0);
function yt_iframe($url){ if (!preg_match('~(v=|youtu\.be/)([^&?/]+)~',$url,$m)) return ''; $id = htmlspecialchars($m[2]); return "<div class='player'><iframe width='100%' height='420' src='https://www.youtube.com/embed/$id' frameborder='0' allowfullscreen></iframe></div>"; }
ob_start(); ?>
<article class="article">
<h2><?= htmlspecialchars($clase['titulo']) ?></h2>
<p><?= nl2br(htmlspecialchars($clase['descripcion'])) ?></p>
<?php if (!empty($clase['youtube_url'])): ?><?= yt_iframe($clase['youtube_url']) ?><?php endif; ?>
<?php if (!empty($clase['archivo_url'])): ?>
<p><a class="btn" href="<?= htmlspecialchars($clase['archivo_url']) ?>" target="_blank">Descargar material</a></p>
<object data="<?= htmlspecialchars($clase['archivo_url']) ?>" type="application/pdf" width="100%" height="500"><p>No se puede previsualizar. Descarga el archivo.</p></object>
<?php endif; ?>
<div class="nav-actions" style="margin:12px 0;">
<?php if ($prev): ?><a class="btn" href="/aula_virtual/aula_virtual.php?curso_id=<?= $cursoId ?>&clase_id=<?= $prev ?>">⟵ Anterior</a><?php endif; ?>
<?php if ($next): ?><a class="btn" href="/aula_virtual/aula_virtual.php?curso_id=<?= $cursoId ?>&clase_id=<?= $next ?>">Siguiente ⟶</a><?php endif; ?>
</div>
<label><input type="checkbox" id="chkDone" <?= $done ? 'checked' : '' ?>> Marcar clase como completada</label>
</article>
<script>
const chk = document.getElementById('chkDone');
if (chk) chk.addEventListener('change', async ()=>{
  await fetch('/aula_virtual/api/marcar_progreso.php', {
    method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({ clase_id: <?= $claseId ?>, done: chk.checked ? 1 : 0 })
  });
});
</script>
<?php $content = ob_get_clean(); $pageTitle = 'Aula Virtual'; include __DIR__.'/views/layout.php';