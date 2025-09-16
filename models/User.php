<?php
// Modelo para la tabla 'estudiante' (representado como User genérico)
class User extends BaseModel {
    public $id_estudiante;
    public $nombre;
    public $correo;
    public $contrasena;
    public $created_at;
    public $updated_at;
}
