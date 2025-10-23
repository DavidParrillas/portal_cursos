<?php 
include __DIR__ . '/../layouts/header.php'; 
require_once __DIR__ . '/../../controllers/ResenaController.php';
require_once __DIR__ . '/../../config/database.php';

$pdo = Database::getInstance();
$resenaController = new ResenaController();
$resenas = $resenaController->listarPorCurso($curso['id_curso']);
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="<?= htmlspecialchars($curso['portada'] ?? '/portal_cursos/assets/img/no_image.png') ?>" 
                     class="img-fluid rounded-start" alt="Portada del curso">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title"><?= htmlspecialchars($curso['titulo']) ?></h3>
                    <p class="text-muted mb-1">
                        <i class="bi bi-person-circle"></i> Instructor: <?= htmlspecialchars($curso['id_instructor']) ?>
                    </p>
                    <p class="text-muted mb-1">
                        <i class="bi bi-tags"></i> Categoría: <?= htmlspecialchars($curso['id_categoria']) ?>
                    </p>
                    <p class="card-text mt-2"><?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
                    <p class="mb-1"><strong>Duración:</strong> <?= htmlspecialchars($curso['duracion']) ?></p>
                    <p class="mb-1"><strong>Modalidad:</strong> <?= htmlspecialchars($curso['modalidad']) ?></p>
                    <p class="mb-1"><strong>Precio:</strong> $<?= number_format($curso['precio'], 2) ?></p>
                    <p class="mb-1"><strong>Cupos disponibles:</strong> <?= htmlspecialchars($curso['cupos']) ?></p>

                    <?php if (!empty($estadisticas)): ?>
                        <div class="mt-3">
                            <p><strong>Inscritos:</strong> <?= $estadisticas['total_inscritos'] ?></p>
                            <p><strong>Calificación promedio:</strong> <?= round($estadisticas['promedio_calificacion'], 1) ?>/5</p>
                        </div>
                    <?php endif; ?>

                    <!-- ==================== LÓGICA DE INSCRIPCIÓN Y ROLES ==================== -->
                    <?php
                    $estaInscrito = false;

                    if (isset($_SESSION['usuario_id']) && $_SESSION['rol'] === 'estudiante') {
                        $idUsuario = $_SESSION['usuario_id'];
                        $idCurso = $curso['id_curso'];

                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscripciones WHERE id_curso = ? AND id_estudiante = ?");
                        $stmt->execute([$idCurso, $idUsuario]);
                        $estaInscrito = $stmt->fetchColumn() > 0;
                    }
                    ?>

                    <div class="mt-3">
                        <?php if (!isset($_SESSION['usuario_id'])): ?>
                            <a href="/portal_cursos/views/auth/login.php" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Inicia sesión para inscribirte
                            </a>

                        <?php elseif ($_SESSION['rol'] === 'estudiante'): ?>
                            <?php if ($estaInscrito): ?>
                                <a href="/portal_cursos/views/aula_virtual.php" class="btn btn-success">
                                    <i class="bi bi-play-circle"></i> Acceder al curso
                                </a>
                            <?php elseif ($curso['cupos'] <= 0): ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="bi bi-x-circle"></i> Sin cupos disponibles
                                </button>
                            <?php else: ?>
                                <button class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> Inscribirse ahora
                                </button>
                            <?php endif; ?>

                        <?php elseif ($_SESSION['rol'] === 'instructor'): ?>
                            <button class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Editar mi curso
                            </button>

                        <?php elseif ($_SESSION['rol'] === 'admin'): ?>
                            <button class="btn btn-dark">
                                <i class="bi bi-gear-fill"></i> Gestionar curso
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== PREVIEW DEL MATERIAL ==================== -->
    <div class="mt-4">
        <h4><i class="bi bi-collection-play"></i> Vista previa del material</h4>
        <?php if (!empty($materiales)): ?>
            <div class="row mt-3">
                <?php foreach ($materiales as $m): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
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
                                    <a href="<?= htmlspecialchars($m['ruta_archivo']) ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                        Descargar archivo
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Este curso aún no tiene materiales disponibles.</p>
        <?php endif; ?>
    </div>

    <!-- ==================== RESEÑAS ==================== -->
    <div class="mt-5">
        <h4><i class="bi bi-star"></i> Reseñas de los estudiantes</h4>
        <?php if (count($resenas) > 0): ?>
            <?php foreach ($resenas as $r): ?>
                <div class="border rounded p-3 mb-3 bg-light">
                    <strong><?= htmlspecialchars($r['nombre_estudiante']) ?></strong><br>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $r['calificacion']): ?>
                            <i class="bi bi-star-fill text-warning"></i>
                        <?php else: ?>
                            <i class="bi bi-star text-muted"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <p class="mt-2"><?= htmlspecialchars($r['comentario']) ?></p>
                    <small class="text-muted"><?= date('d/m/Y', strtotime($r['creado_en'])) ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Aún no hay reseñas para este curso.</p>
        <?php endif; ?>
    </div>

    <!-- ==================== FORMULARIO PARA NUEVA RESEÑA ==================== -->
    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['rol'] === 'estudiante'): ?>
        <div class="mt-4">
            <h5>Deja tu reseña</h5>
            <form action="index.php?controller=Resena&action=guardarResena" method="POST">
                <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">

                <div class="mb-3">
                    <label for="calificacion" class="form-label">Calificación:</label><br>
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>

