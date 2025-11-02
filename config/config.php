<?php
// Configuración general del sistema

define('APP_NAME', 'Portal de Cursos y Talleres');
define('BASE_URL', '/');
// Agregar más configuraciones globales según sea necesario

// Prefijo para módulo Aula Virtual
if (!defined('AULA_BASE')) define('AULA_BASE', '/portal_cursos');

require_once __DIR__ . '/database.php';
$db = Database::getInstance();
