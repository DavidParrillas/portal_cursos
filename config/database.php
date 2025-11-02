<?php
/**
 * Clase Database
 * Gestiona la conexión a la BD usando el patrón Singleton para asegurar una única instancia.
 */
class Database {
    /**
     * @var self|null Instancia única de la clase (Singleton).
     */
    private static $instance = null;
    /**
     * @var PDO Objeto de conexión a la base de datos.
     */
    private $pdo;

    /**
     * Constructor privado para prevenir la instanciación directa y establecer la conexión.
     */
    private function __construct() {
        $host = 'localhost';
        $db   = 'grupo03_bdappweb';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            // Lanza excepciones en caso de errores.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Obtener resultados como array asociativo.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Desactiva emulación de sentencias preparadas para mayor seguridad.
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // En producción, registrar el error en lugar de mostrarlo.
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Obtiene la instancia de la conexión a la base de datos.
     * @return PDO El objeto PDO para interactuar con la BD.
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}