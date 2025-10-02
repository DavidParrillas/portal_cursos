<?php
// Modelo de materiales
class Material extends BaseModel {
    public $id_material;
    public $id_curso;
    public $id_usuario;
    public $titulo;
    public $ruta_archivo;
    public $creado_en;
}
