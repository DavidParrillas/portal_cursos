<?php
class Material {
    private $id_material;
    private $id_curso;
    private $id_usuario;
    private $titulo;
    private $ruta_archivo;
    private $creado_en;
    
    // Getters
    public function getIdMaterial() {
        return $this->id_material;
    }
    
    public function getIdCurso() {
        return $this->id_curso;
    }
    
    public function getIdUsuario() {
        return $this->id_usuario;
    }
    
    public function getTitulo() {
        return $this->titulo;
    }
    
    public function getRutaArchivo() {
        return $this->ruta_archivo;
    }
    
    public function getCreadoEn() {
        return $this->creado_en;
    }
    
    // Setters
    public function setIdMaterial($id_material) {
        $this->id_material = $id_material;
    }
    
    public function setIdCurso($id_curso) {
        $this->id_curso = $id_curso;
    }
    
    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }
    
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    
    public function setRutaArchivo($ruta_archivo) {
        $this->ruta_archivo = $ruta_archivo;
    }
    
    public function setCreadoEn($creado_en) {
        $this->creado_en = $creado_en;
    }
}