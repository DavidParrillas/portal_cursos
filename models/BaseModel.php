<?php
/**
 * Clase base para todos los modelos
 * Proporciona la conexión PDO a todos los modelos que la heredan
 */
class BaseModel {
    protected $pdo;

    public function __construct($pdo) {
        if (!$pdo instanceof PDO) {
            throw new Exception("Se requiere una instancia válida de PDO");
        }
        $this->pdo = $pdo;
    }
}
?>