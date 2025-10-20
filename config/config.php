<?php
// Configuración general del sistema
define('APP_NAME', 'Portal de Cursos y Talleres');

// BASE_URL tal como lo usa el código del main.
if (!defined('BASE_URL')) {
  define('BASE_URL', '/');   // <- lo que usa el main
}

// Prefijo SOLO para el módulo Aula Virtual
if (!defined('AULA_BASE')) {
  define('AULA_BASE', '/portal_cursos');
}

// Reutilizar la clase Database del proyecto principal
require_once __DIR__ . '/database.php';

// Crear la instancia PDO global
$db = Database::getInstance();