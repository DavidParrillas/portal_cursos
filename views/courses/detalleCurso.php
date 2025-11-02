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
    echo '<div class="container mt-5"><h3 class="text-danger">Curso no válido.</h3></div>';
    exit;
}

$idCurso = (int) $_GET['id'];

// Cargar curso
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id_curso = ?");
$stmt->execute([$idCurso]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    echo '<div class="container mt-5"><h3 class="text-danger">El curso no existe o fue eliminado.</h3></div>';
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
    $stmt = $pdo->prepare("SELECT r.*, u.nombre_completo AS nombre_estudiante FROM resenas r 
        JOIN usuarios u ON r.id_estudiante = u.id_usuario 
        WHERE r.id_curso = ? ORDER BY r.creado_en DESC");
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($curso['titulo']) ?> - Detalle del Curso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ===========================
           Estilos exclusivos de detalleCurso.php
           =========================== */
        body {
            background: #e8f0ff !important;
            font-family: "Poppins", sans-serif !important;
        }

        .container {
            max-width: 1100px !important;
            margin: 30px auto !important;
            background: #f9fbff !important;
            border-radius: 12px !important;
            padding: 25px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        }

        .card {
            border-radius: 12px !important;
            overflow: hidden;
        }

        .card-body h3 {
            color: #1e293b;
            font-weight: 600;
        }

        .card-body p, label, small {
            color: #334155;
        }

        .btn-primary {
            background-color: #4f46e5 !important;
            border: none !important;
        }

        .btn-primary:hover {
            background-color: #4338ca !important;
        }

        .btn-success {
            background-color: #16a34a !important;
            border: none !important;
        }

        .btn-warning {
            background-color: #f59e0b !important;
            border: none !important;
        }

        .btn-dark {
            background-color: #1e293b !important;
            border: none !important;
        }

        .resena {
            background-color: #f1f5f9 !important;
            border-left: 5px solid #6366f1 !important;
            padding: 15px !important;
            border-radius: 8px !important;
            margin-bottom: 10px !important;
        }

        textarea.form-control, input[type="radio"] {
            accent-color: #4f46e5 !important;
        }

        .card-material {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
            padding: 15px !important;
            margin-bottom: 15px !important;
        }

        iframe, embed {
            border-radius: 8px !important;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="card shadow">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="<?= htmlspecialchars($curso['portada'] ?? '/portal_cursos/public/assets/img/placeholders/course-default.png') ?>" class="img-fluid rounded-start" alt="Portada del curso">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3><?= htmlspecialchars($curso['titulo']) ?></h3>
                    <p class="text-muted mb-1">Instructor: <?= htmlspecialchars($curso['id_instructor']) ?></p>
                    <p class="text-muted mb-1">Categoría: <?= htmlspecialchars($curso['id_categoria'] ?? '') ?></p>
                    <p class="mt-2"><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
                    <p><strong>Duración:</strong> <?= htmlspecialchars($curso['duracion'] ?? '') ?></p>
                    <p><strong>Modalidad:</strong> <?= htmlspecialchars($curso['modalidad'] ?? '') ?></p>
                    <p><strong>Precio:</strong> $<?= number_format((float)($curso['precio'] ?? 0), 2) ?></p>
                    <p><strong>Cupos disponibles:</strong> <?= htmlspecialchars($curso['cupos'] ?? 0) ?></p>

                    <?php if (!empty($estadisticas)): ?>
                    <div class="mt-3">
                        <p><strong>Inscritos:</strong> <?= (int)$estadisticas['total_inscritos'] ?></p>
                        <p><strong>Calificación promedio:</strong> <?= number_format((float)$estadisticas['promedio_calificacion'], 1) ?>/5</p>
                    </div>
                    <?php endif; ?>

                    <div class="mt-3">
                        <?php if (!$sessionUserId): ?>
                            <a href="/portal_cursos/views/auth/login.php" class="btn btn-outline-primary">Inicia sesión para inscribirte</a>
                        <?php elseif ($sessionUserRol === 'estudiante'): ?>
                            <?php if ($estaInscrito): ?>
                                <a href="/portal_cursos/views/aula_virtual.php" class="btn btn-success">Acceder al curso</a>
                            <?php elseif ((int)$curso['cupos'] <= 0): ?>
                                <button class="btn btn-secondary" disabled>Sin cupos disponibles</button>
                            <?php else: ?>
                                <form action="/portal_cursos/controllers/InscripcionController.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id_curso" value="<?= (int)$curso['id_curso'] ?>">
                                    <button type="submit" class="btn btn-primary">Inscribirse ahora</button>
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

    <!-- Materiales -->
    <div class="mt-4">
        <h4>Vista previa del material</h4>
        <?php if (!empty($materiales)): ?>
        <div class="row mt-3">
            <?php foreach ($materiales as $m): ?>
            <div class="col-md-6 mb-3">
                <div class="card-material">
                    <h5><?= htmlspecialchars($m['titulo']) ?></h5>
                    <?php if (str_contains($m['ruta_archivo'], 'youtube.com')): ?>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?= htmlspecialchars($m['ruta_archivo']) ?>" frameborder="0" allowfullscreen></iframe>
                        </div>
                    <?php elseif (preg_match('/\.(jpg|jpeg|png)$/i', $m['ruta_archivo'])): ?>
                        <img src="<?= htmlspecialchars($m['ruta_archivo']) ?>" class="img-fluid rounded mt-2" alt="Material">
                    <?php elseif (preg_match('/\.pdf$/i', $m['ruta_archivo'])): ?>
                        <embed src="<?= htmlspecialchars($m['ruta_archivo']) ?>" type="application/pdf" width="100%" height="300px" />
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($m['ruta_archivo']) ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2">Descargar archivo</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="text-muted">Este curso aún no tiene materiales disponibles.</p>
        <?php endif; ?>
    </div>

    <!-- Reseñas -->
    <div class="mt-5">
        <h4>Reseñas de los estudiantes</h4>
        <?php if (!empty($resenas)): ?>
            <?php foreach ($resenas as $r): ?>
            <div class="resena">
                <strong><?= htmlspecialchars($r['nombre_estudiante'] ?? $r['nombre_completo'] ?? 'Estudiante') ?></strong><br>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?= $i <= (int)$r['calificacion'] ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                <?php endfor; ?>
                <p class="mt-2"><?= htmlspecialchars($r['comentario'] ?? '') ?></p>
                <small class="text-muted"><?= date('d/m/Y', strtotime($r['creado_en'] ?? $r['fecha'] ?? date('Y-m-d'))) ?></small>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Aún no hay reseñas para este curso.</p>
        <?php endif; ?>
    </div>

    <!-- Formulario reseña -->
    <?php if ($sessionUserId && $sessionUserRol === 'estudiante'): ?>
    <div class="mt-4">
        <h5>Deja tu reseña</h5>
        <form action="/portal_cursos/controllers/ResenaController.php?action=guardarResena" method="POST">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


