<?php
// Credenciales por defecto de XAMPP
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1'; // o 'localhost'
$DB_NAME = getenv('DB_NAME') ?: 'bd_appweb';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

try {
  $db = new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
  );
} catch (PDOException $e) {
  http_response_code(500);
  die("Error de conexión a BD: " . $e->getMessage());
}
