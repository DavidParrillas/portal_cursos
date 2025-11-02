<?php
/**
 * Aula Virtual — Vista de materiales del curso
 * BD usada:
 *   - inscripciones(id_curso, id_estudiante)
 *   - cursos(id_curso, titulo, descripcion)
 *   - materiales(id_material, id_curso, titulo, ruta_archivo)
 */

require_once __DIR__ . '/../config/config.php';  // trae AULA_BASE y $db (PDO)
require_once __DIR__ . '/lib/auth.php';          // debe exponer currentUserId()

// ————————————————————————————————————————————————————————————
// Helpers
// ————————————————————————————————————————————————————————————
/** Detecta si un link apunta a YouTube (watch, short, youtu.be, embed) */
function esVideoYouTube(string $url): bool {
  $u = strtolower($url);
  return str_contains($u, 'youtube.com') || str_contains($u, 'youtu.be');
}

/** Convierte cualquier URL de YouTube a forma embeible: https://www.youtube.com/embed/ID */
function youtubeEmbedSrc(string $url): ?string {
  // Soporta: watch?v=ID, youtu.be/ID, /embed/ID, /shorts/ID
  if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/)([A-Za-z0-9_-]{6,})~', $url, $m)) {
    $id = $m[1];
    // Si prefieres privacy-enhanced: youtube-nocookie.com
    return "https://www.youtube.com/embed/$id";
  }
  return null;
}

// ————————————————————————————————————————————————————————————
// Entrada
// ————————————————————————————————————————————————————————————
$usuarioId = currentUserId();                        // en modo demo podría ser 1
$cursoId   = (int)($_GET['curso_id'] ?? 0);
if (!$cursoId) {
  http_response_code(400);
  die('<p>ID de curso no válido.</p>');
}

// ————————————————————————————————————————————————————————————
// Control de acceso: solo inscritos
// ————————————————————————————————————————————————————————————
$st = $db->prepare("SELECT 1 FROM inscripciones WHERE id_curso=? AND id_estudiante=?");
$st->execute([$cursoId, $usuarioId]);
if (!$st->fetch()) {
  http_response_code(403);
  die('<p>Acceso restringido. Inscríbete en el curso.</p>');
}

// ————————————————————————————————————————————————————————————
// Datos del curso
// ————————————————————————————————————————————————————————————
$st = $db->prepare("SELECT titulo, descripcion FROM cursos WHERE id_curso=?");
$st->execute([$cursoId]);
$curso = $st->fetch(PDO::FETCH_ASSOC);
if (!$curso) {
  http_response_code(404);
  die('<p>Curso no encontrado.</p>');
}

// ————————————————————————————————————————————————————————————
// Materiales (lista y material actual)
// ————————————————————————————————————————————————————————————
$stm = $db->prepare("SELECT id_material, titulo, ruta_archivo FROM materiales WHERE id_curso=? ORDER BY id_material ASC");
$stm->execute([$cursoId]);
$materiales = $stm->fetchAll(PDO::FETCH_ASSOC);

// material actual por parámetro o primero
$matId  = (int)($_GET['material_id'] ?? ($materiales[0]['id_material'] ?? 0));
$actual = null;
foreach ($materiales as $m) {
  if ((int)$m['id_material'] === $matId) { $actual = $m; break; }
}
if (!$actual && !empty($materiales)) $actual = $materiales[0];

// ————————————————————————————————————————————————————————————
// Render del contenido central (lo inyecta en views/layout.php)
// ————————————————————————————————————————————————————————————
ob_start();
?>
<article class="article">
  <h2><?= htmlspecialchars($curso['titulo']) ?></h2>
  <?php if (!empty($curso['descripcion'])): ?>
    <p><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
  <?php endif; ?>

  <?php if ($actual): ?>
    <h3><?= htmlspecialchars($actual['titulo']) ?></h3>

    <?php if (esVideoYouTube($actual['ruta_archivo'])):
          $embed = youtubeEmbedSrc($actual['ruta_archivo']); ?>
      <?php if ($embed): ?>
        <div class="player">
          <iframe
            width="100%" height="420"
            src="<?= htmlspecialchars($embed) ?>"
            frameborder="0"
            allowfullscreen
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share">
          </iframe>
        </div>
      <?php else: ?>
        <p>El enlace de YouTube no es válido.</p>
      <?php endif; ?>
    <?php else: ?>
      <p><a class="btn" href="<?= htmlspecialchars($actual['ruta_archivo']) ?>" target="_blank">Abrir/Descargar material</a></p>
      <object data="<?= htmlspecialchars($actual['ruta_archivo']) ?>" type="application/pdf" width="100%" height="500">
        <p>No se puede previsualizar. Descarga el archivo.</p>
      </object>
    <?php endif; ?>

    <div class="nav-actions" style="margin:12px 0;">
      <?php
        $keys = array_column($materiales, 'id_material');
        $pos  = $actual ? array_search($actual['id_material'], $keys) : false;
        $prev = ($pos !== false && $pos > 0) ? $materiales[$pos - 1]['id_material'] : null;
        $next = ($pos !== false && $pos < count($materiales) - 1) ? $materiales[$pos + 1]['id_material'] : null;
      ?>
      <?php if ($prev): ?>
        <a class="btn" href="<?= AULA_BASE ?>/aula_virtual/aula_virtual.php?curso_id=<?= $cursoId ?>&material_id=<?= $prev ?>">⟵ Anterior</a>
      <?php endif; ?>
      <?php if ($next): ?>
        <a class="btn" href="<?= AULA_BASE ?>/aula_virtual/aula_virtual.php?curso_id=<?= $cursoId ?>&material_id=<?= $next ?>">Siguiente ⟶</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <p>No hay materiales disponibles para este curso.</p>
  <?php endif; ?>
</article>
<?php
$content   = ob_get_clean();
$pageTitle = 'Aula Virtual';

// Usa el layout común del módulo
include __DIR__ . '/views/layout.php';
