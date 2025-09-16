<?php
/**
 * Clase Database
 *
 * Gestiona la conexión a la base de datos utilizando el patrón de diseño Singleton.
 * Esto asegura que solo exista una única instancia de la conexión PDO en toda la aplicación,
 * optimizando los recursos y manteniendo la consistencia.
 */
class Database {
    /**
     * @var self|null La única instancia de la clase Database (Singleton).
     */
    private static $instance = null;
    /**
     * @var PDO El objeto de conexión a la base de datos.
     */
    private $pdo;

    /**
     * Constructor privado para prevenir la creación de nuevas instancias directamente.
     * Se encarga de establecer la conexión con la base de datos.
     */
    private function __construct() {
        // Parámetros de conexión a la base de datos.
        $host = 'localhost';
        $db   = 'portal_cursos';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        // DSN (Data Source Name) - define el host, la base de datos y el charset.
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        // Opciones para la conexión PDO.
        $options = [
            // Lanza excepciones en caso de errores, en lugar de warnings.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Establece el modo de obtención de resultados por defecto a un array asociativo.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Desactiva la emulación de sentencias preparadas para mayor seguridad (previene inyección SQL).
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Intenta crear la instancia de PDO.
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Si la conexión falla, detiene la ejecución y muestra un mensaje de error.
            // En un entorno de producción, esto debería registrarse en un archivo de log en lugar de mostrarse al usuario.
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Método estático público para obtener la instancia de la conexión a la base de datos.
     *
     * Si la instancia no existe, la crea. Si ya existe, la retorna.
     *
     * @return PDO El objeto PDO para interactuar con la base de datos.
     */
    public static function getInstance() {
        // Si aún no se ha creado la instancia...
        if (self::$instance === null) {
            // ...la crea. Esto solo ocurre una vez.
            self::$instance = new self();
        }
        // Retorna el objeto PDO contenido en la instancia.
        return self::$instance->pdo;
    }
}