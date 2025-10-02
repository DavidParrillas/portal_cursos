<?php
// Modelo para la tabla 'usuarios'
class User extends BaseModel {
    public $id_usuario;
    public $nombre_completo;
    public $correo;
    public $contrasena_hash;
    public $estado;
    public $creado_en;
    public $actualizado_en;
}
