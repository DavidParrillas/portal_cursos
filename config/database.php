<?php
/**
 * Database singleton wrapper.
 * Proporciona Database::getInstance() y también expone $pdo para compatibilidad.
 */

class Database {
    /** @var PDO|null */
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                $dsn = 'mysql:host=localhost;dbname=portal_cursos;charset=utf8mb4';
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];
                self::$instance = new PDO($dsn, 'root', '', $options);
            } catch (PDOException $e) {
                error_log("ERROR CONEXIÓN BD: " . $e->getMessage());
                throw $e;
            }
        }
        return self::$instance;
    }
}

// Compatibilidad: crear $pdo para código que espere esa variable
try {
    $pdo = Database::getInstance();
} catch (Exception $e) {
    die("Error de conexión a la base de datos");
}
?>