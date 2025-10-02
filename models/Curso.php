<?php
// Modelo de curso
class Course extends BaseModel {
    public $id_curso;
    public $id_instructor;
    public $titulo;
    public $slug;
    public $descripcion;
    public $duracion;
    public $modalidad;
    public $precio;
    public $fecha_inicio;
    public $cupos;
    public $estado;
    public $creado_en;
    public $actualizado_en;
}
