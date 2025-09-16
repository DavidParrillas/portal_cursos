<?php
// Modelo para la tabla 'administrador'
class Administrador extends BaseModel {
    public $id_admin;
    public $nombre;
    public $correo;
    public $contrasena;
    public $created_at;
    public $updated_at;
}