<?php
// Modelo para la tabla 'instructor'
class Instructor extends BaseModel {
    public $id_instructor;
    public $nombre;
    public $correo;
    public $contrasena;
    public $created_at;
    public $updated_at;
}