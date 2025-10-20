<?php require_once __DIR__ . '/../../config/config.php';

// El curso viene en la URL
$cursoId = (int)($_GET['curso_id'] ?? 0);
$matId   = (int)($_GET['material_id'] ?? 0);

// Lista de materiales del curso
$stm = $db->prepare("SELECT id_material, titulo FROM materiales WHERE id_curso=? ORDER BY id_material ASC");
$stm->execute([$cursoId]);
$items = $stm->fetchAll(PDO::FETCH_ASSOC);

echo '<div class="section">';
echo '<h4>Materiales del curso</h4><ul>';
foreach ($items as $it) {
  $active = ($it['id_material'] == $matId || (!$matId && $it === reset($items))) ? 'class="active"' : '';
  $href = AULA_BASE . "/aula_virtual/aula_virtual.php?curso_id={$cursoId}&material_id={$it['id_material']}";
  echo "<li><a $active href=\"$href\">" . htmlspecialchars($it['titulo']) . "</a></li>";
}
echo '</ul></div>';
