<?php
// Modelo de materiales
class Material extends BaseModel {
    public $id_material;
    public $id_curso;
    public $id_instructor;
    public $titulo;
    public $archivo;
    public $created_at;
}
