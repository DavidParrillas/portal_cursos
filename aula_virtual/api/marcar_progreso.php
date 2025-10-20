<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../lib/auth.php';


header('Content-Type: application/json');

$payload = json_decode(file_get_contents('php://input'), true) ?: [];
$usuarioId = currentUserId();
$claseId = (int)($payload['clase_id'] ?? 0);
$done = (int)($payload['done'] ?? 0);

$stmt = $db->prepare(
  "INSERT INTO progreso_clase (usuario_id, clase_id, completada)
   VALUES (?,?,?)
   ON DUPLICATE KEY UPDATE completada = VALUES(completada)"
);
$stmt->execute([$usuarioId, $claseId, $done]);

echo json_encode(['ok' => true]);
