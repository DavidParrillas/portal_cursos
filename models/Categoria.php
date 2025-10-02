<?php
// Modelo para la tabla 'categoria'
class Categoria extends BaseModel {
    public $id_categoria;
    public $nombre;
    public $slug;

    // Getters
    public function getIdCategoria() {
        return $this->id_categoria;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    public function getSlug() {
        return $this->slug;
    }
    
    // Setters
    public function setIdCategoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function setSlug($slug) {
        $this->slug = $slug;
    }
}