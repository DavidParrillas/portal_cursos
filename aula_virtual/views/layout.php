<?php require_once __DIR__ . '/../../config/config.php'; ?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $pageTitle ?? 'Aula Virtual' ?></title>
  <link rel="stylesheet" href="<?= AULA_BASE ?>/aula_virtual/assets/css/aula.css">
</head>
<body>
  <header class="topbar">
    <div class="brand"><div class="dot"></div><span>Aula Virtual</span></div>
    <nav class="topnav">
      <a href="<?= AULA_BASE ?>/">Inicio</a>
      <a href="<?= AULA_BASE ?>/cursos.php">Cursos</a>
    </nav>
  </header>

  <div class="container">
    <aside class="sidebar">
      <?php include __DIR__ . '/../partials/sidebar_curso.php'; ?>
    </aside>
    <main class="content">
      <?= $content ?? '' ?>
    </main>
  </div>
</body>
</html>
