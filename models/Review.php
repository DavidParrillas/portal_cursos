<?php
// Modelo de reseñas
class Review extends BaseModel {
    public $id_resena;
    public $id_estudiante;
    public $id_curso;
    public $comentario;
    public $calificacion;
    public $creado_en;
}
