<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../controllers/ResenaController.php';

// Instancia PDO
$pdo = Database::getInstance();

// Helper para compatibilidad de nombres de sesión
$sessionUserId = $_SESSION['user_id'] ?? $_SESSION['usuario_id'] ?? null;
$sessionUserRol = $_SESSION['user_rol'] ?? $_SESSION['rol'] ?? null;

// Validar ID curso
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $pageTitle = "Error - Curso no válido";
    ob_start();
    echo '<div class="container mt-5"><h3 class="text-danger">Curso no válido.</h3></div>';
    $content = ob_get_clean();
    require_once __DIR__ . '/../layouts/layout.php';
    exit;
}

$idCurso = (int) $_GET['id'];

// Cargar curso
$stmt = $pdo->prepare("SELECT c.*, u.nombre_completo as nombre_instructor FROM cursos c LEFT JOIN usuarios u ON c.id_instructor = u.id_usuario WHERE c.id_curso = ?");
$stmt->execute([$idCurso]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    $pageTitle = "Error - Curso no encontrado";
    ob_start();
    echo '<div class="container mt-5"><h3 class="text-danger">El curso no existe o fue eliminado.</h3></div>';
    $content = ob_get_clean();
    require_once __DIR__ . '/../layouts/layout.php';
    exit;
}

// Materiales
$stmt = $pdo->prepare("SELECT * FROM materiales WHERE id_curso = ? ORDER BY creado_en DESC");
$stmt->execute([$idCurso]);
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Reseñas
$resenaController = new ResenaController($pdo);
if (method_exists($resenaController, 'listarPorCurso')) {
    $resenas = $resenaController->listarPorCurso($idCurso);
} elseif (method_exists($resenaController, 'obtenerPorCurso')) {
    $resenas = $resenaController->obtenerPorCurso($idCurso);
} else {
    $stmt = $pdo->prepare("SELECT r.*, u.nombre_completo AS nombre_estudiante FROM resenas r JOIN usuarios u ON r.id_estudiante = u.id_usuario WHERE r.id_curso = ? ORDER BY r.creado_en DESC");
    $stmt->execute([$idCurso]);
    $resenas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Estadísticas
$stmt = $pdo->prepare("
    SELECT COUNT(i.id_inscripcion) AS total_inscritos, 
           COALESCE(AVG(r.calificacion), 0) AS promedio_calificacion
    FROM cursos c
    LEFT JOIN inscripciones i ON c.id_curso = i.id_curso
    LEFT JOIN resenas r ON c.id_curso = r.id_curso
    WHERE c.id_curso = ?
");
$stmt->execute([$idCurso]);
$estadisticas = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['total_inscritos' => 0, 'promedio_calificacion' => 0];

// Lógica inscripción
$estaInscrito = false;
if ($sessionUserId && $sessionUserRol === 'estudiante') {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE id_curso = ? AND id_estudiante = ?");
    $stmt->execute([$idCurso, $sessionUserId]);
    $estaInscrito = (bool) $stmt->fetchColumn();
}

$pageTitle = htmlspecialchars($curso['titulo']) . " - Detalle del Curso";
ob_start();
?>
<link rel="stylesheet" href="/portal_cursos/public/assets/css/detalleCurso.css?v=<?php echo time(); ?>">
<div class="curso-detalle">
    <div class="curso-main-content">
        <div class="curso-header">
            <h1 class="curso-titulo"><?= htmlspecialchars($curso['titulo']) ?></h1>
            <div class="curso-meta">
                <div class="curso-meta-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Instructor: <?= htmlspecialchars($curso['nombre_instructor']) ?></span>
                </div>
                <div class="curso-meta-item">
                    <i class="fas fa-folder"></i>
                    <span>Categoría: <?= htmlspecialchars($curso['id_categoria'] ?? 'Sin categoría') ?></span>
                </div>
                <div class="curso-meta-item">
                    <i class="fas fa-users"></i>
                    <span>Inscritos: <?= (int)$estadisticas['total_inscritos'] ?></span>
                </div>
                <div class="curso-meta-item">
                    <i class="fas fa-star"></i>
                    <span>Calificación: <?= number_format((float)$estadisticas['promedio_calificacion'], 1) ?>/5</span>
                </div>
            </div>
        </div>

        <div class="curso-descripcion">
            <p><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
        </div>

        <!-- Materiales -->
        <div class="materiales-section">
            <h4 class="materiales-titulo">Vista previa del material</h4>
            <?php if (!empty($materiales)): ?>
            <div class="materiales-lista">
                <?php foreach ($materiales as $m): ?>
                <div class="material-item">
                    <div class="material-icon"><i class="far fa-file-alt"></i></div>
                    <div class="material-info">
                        <div class="material-titulo"><?= htmlspecialchars($m['titulo']) ?></div>
                        <div class="material-meta">Tipo: <?php
                            $extension = pathinfo($m['ruta_archivo'], PATHINFO_EXTENSION);
                            if (str_contains($m['ruta_archivo'], 'youtube.com')) {
                                echo 'Video';
                            } else {
                                echo strtoupper($extension);
                            }
                        ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p class="text-muted">Este curso aún no tiene materiales disponibles.</p>
            <?php endif; ?>
        </div>

        <!-- Reseñas -->
        <div class="resenas-section">
            <h4 class="resenas-titulo">Reseñas de los estudiantes</h4>
            <?php if (!empty($resenas)): ?>
                <div class="resenas-lista">
                <?php foreach ($resenas as $r): ?>
                <div class="resena-item">
                    <div class="resena-header">
                        <div class="resena-autor"><?= htmlspecialchars($r['nombre_estudiante'] ?? $r['nombre_completo'] ?? 'Estudiante') ?></div>
                        <div class="resena-fecha"><?= date('d/m/Y', strtotime($r['creado_en'] ?? $r['fecha'] ?? date('Y-m-d'))) ?></div>
                    </div>
                    <div class="resena-calificacion">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= (int)$r['calificacion'] ? '★' : '☆'; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="resena-contenido"><?= htmlspecialchars($r['comentario'] ?? '') ?></div>
                </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Aún no hay reseñas para este curso.</p>
            <?php endif; ?>
        </div>

        <!-- Formulario reseña -->
        <?php if ($sessionUserId && $sessionUserRol === 'estudiante'): ?>
        <div class="resena-form">
            <h5>Deja tu reseña</h5>
            <form id="form-resena" action="/portal_cursos/controllers/ResenaController.php?action=guardarResena" method="POST">
                <input type="hidden" name="id_curso" value="<?= (int)$curso['id_curso'] ?>">
                <input type="hidden" name="id_estudiante" value="<?= (int)$sessionUserId ?>">
                <div class="mb-3">
                    <label class="form-label">Calificación:</label><br>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" id="estrella<?= $i ?>" name="calificacion" value="<?= $i ?>" required>
                        <label for="estrella<?= $i ?>" style="cursor:pointer;font-size:1.5rem;color:gold;">&#9733;</label>
                    <?php endfor; ?>
                </div>
                <div class="mb-3">
                    <textarea name="comentario" class="form-control" rows="3" placeholder="Escribe tu reseña..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar reseña</button>
            </form>
        </div>
        <?php else: ?>
            <p class="text-muted mt-3">Inicia sesión como estudiante para dejar una reseña.</p>
        <?php endif; ?>
    </div>

    <div class="curso-sidebar">
        <div class="curso-sidebar-card">
            <img src="<?= htmlspecialchars($curso['portada'] ?? '/portal_cursos/public/assets/img/placeholders/course-default.png') ?>" 
                 class="curso-imagen" 
                 alt="Portada del curso">
            <div class="curso-sidebar-body">
                <div class="curso-precio">$<?= number_format((float)($curso['precio'] ?? 0), 2) ?></div>
                <div class="curso-acciones">
                    <?php if (!$sessionUserId): ?>
                        <a href="/portal_cursos/views/auth/login.php" class="btn btn-primary">Inicia sesión para inscribirte</a>
                    <?php elseif ($sessionUserRol === 'estudiante'): ?>
                        <?php if ($estaInscrito): ?>
                            <a href="/portal_cursos/views/aula_virtual.php" class="btn btn-success">Acceder al curso</a>
                        <?php elseif ((int)$curso['cupos'] <= 0): ?>
                            <button class="btn btn-secondary" disabled>Sin cupos disponibles</button>
                        <?php else: ?>
                            <form action="/portal_cursos/public/paypal_init.php" method="POST" class="d-inline">
                                <input type="hidden" name="id_curso" value="<?= (int)$curso['id_curso'] ?>">
                                <input type="hidden" name="precio_curso" value="<?= htmlspecialchars($curso['precio']) ?>">
                                <input type="hidden" name="titulo_curso" value="<?= htmlspecialchars($curso['titulo']) ?>">
                                <button type="submit" class="btn btn-primary">Pagar con PayPal</button>
                            </form>
                        <?php endif; ?>
                    <?php elseif ($sessionUserRol === 'instructor'): ?>
                        <button class="btn btn-warning">Editar mi curso</button>
                    <?php elseif ($sessionUserRol === 'administrador' || $sessionUserRol === 'admin'): ?>
                        <button class="btn btn-dark">Gestionar curso</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formResena = document.getElementById('form-resena');
    if (formResena) {
        formResena.addEventListener('submit', function(event) {
            event.preventDefault(); // Detener el envío del formulario

            alertify.confirm(
                'Confirmar Envío', 
                '¿Estás seguro de que quieres enviar tu reseña?', 
                function() { 
                    formResena.submit(); // Si el usuario confirma, se envía el formulario
                }, 
                function() { 
                    alertify.error('Envío cancelado'); // Si el usuario cancela
                }
            ).set('labels', {ok:'Enviar', cancel:'Cancelar'});
        });
    }
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layouts/layout.php';
?>
