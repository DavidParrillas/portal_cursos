<?php 
$pageTitle = "Panel Cursos - Curzilla";
ob_start();
?>

<main class="courses-section">
    
</main>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/layout.php';
?>