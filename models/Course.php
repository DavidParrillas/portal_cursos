<?php
// Modelo de curso
class Course extends BaseModel {
    public $id_curso;
    public $id_instructor;
    public $id_categoria;
    public $titulo;
    public $descripcion;
    public $duracion;
    public $modalidad;
    public $precio;
    public $fecha_inicio;
    public $cupos;
    public $aprobado;
    public $created_at;
    public $updated_at;
}
