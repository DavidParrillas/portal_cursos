<?php
class Reporte{
    private $conn;
    private $table_name = "reportes";

    public $id;
    public $titulo;
    public $descripcion;
    public $fecha_creacion;
    public $usuario_id;

    public function __construct($db){
        $this->conn = $db;
    }
}