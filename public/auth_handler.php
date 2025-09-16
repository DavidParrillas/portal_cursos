<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$pdo = Database::getInstance();
$authController = new AuthController($pdo);

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'register':
        $authController->register();
        break;
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    default:
        header('Location: /portal_cursos/public/index.php');
        exit();
}