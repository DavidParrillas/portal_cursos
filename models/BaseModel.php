<?php
// Modelo base para herencia de modelos específicos
class BaseModel {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
}
