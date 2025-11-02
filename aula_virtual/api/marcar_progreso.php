<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../lib/auth.php';

$usuarioId = currentUserId();
$data = json_decode(file_get_contents('php://input'), true);
$materialId = (int)($data['material_id'] ?? 0);
$done = (int)($data['done'] ?? 0);

if (!$materialId || !$usuarioId) { http_response_code(400); exit; }

$stmt = $db->prepare("INSERT INTO progreso_material (id_usuario, id_material, completado)
                      VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE completado=?");
$stmt->execute([$usuarioId, $materialId, $done, $done]);

echo json_encode(['ok' => true]);
