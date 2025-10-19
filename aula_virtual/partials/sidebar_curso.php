<?php
require_once __DIR__.'/../lib/db.php';
$cursoId = (int)($_GET['curso_id'] ?? 1);
$claseActualId = (int)($_GET['clase_id'] ?? 0);
$seccionesStmt = $db->prepare("SELECT id,titulo FROM secciones WHERE curso_id=? ORDER BY orden,id");
$seccionesStmt->execute([$cursoId]);
$secciones = $seccionesStmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($secciones as $sec) {
  echo "<div class='section'>";
  echo "<h4>".htmlspecialchars($sec['titulo'])."</h4><ul>";
  $clases = $db->prepare("SELECT id,titulo FROM clases WHERE seccion_id=? ORDER BY orden,id");
  $clases->execute([$sec['id']]);
  while ($c = $clases->fetch(PDO::FETCH_ASSOC)) {
    $active = ($c['id']==$claseActualId) ? 'class="active"' : '';
    echo "<li><a $active href='/aula_virtual/aula_virtual.php?curso_id=$cursoId&clase_id={$c['id']}'>".htmlspecialchars($c['titulo'])."</a></li>";
  }
  echo "</ul></div>";
}