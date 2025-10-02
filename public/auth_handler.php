<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AutenticacionController.php';

$pdo = Database::getInstance();
$autenticacionController = new AutenticacionController($pdo);

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'register':
        $autenticacionController->register();
        break;
    case 'login':
        $autenticacionController->login();
        break;
    case 'logout':
        $autenticacionController->logout();
        break;
    default:
        header('Location: /portal_cursos/public/index.php');
        exit();
}