<?php
class Curso {
    private $id_curso;
    private $id_instructor;
    private $titulo;
    private $slug;
    private $descripcion;
    private $duracion;
    private $modalidad;
    private $precio;
    private $fecha_inicio;
    private $cupos;
    private $estado;
    private $creado_en;
    private $actualizado_en;
    
    // Getters
    public function getIdCurso() {
        return $this->id_curso;
    }
    
    public function getIdInstructor() {
        return $this->id_instructor;
    }
    
    public function getTitulo() {
        return $this->titulo;
    }
    
    public function getSlug() {
        return $this->slug;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }
    
    public function getDuracion() {
        return $this->duracion;
    }
    
    public function getModalidad() {
        return $this->modalidad;
    }
    
    public function getPrecio() {
        return $this->precio;
    }
    
    public function getFechaInicio() {
        return $this->fecha_inicio;
    }
    
    public function getCupos() {
        return $this->cupos;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function getCreadoEn() {
        return $this->creado_en;
    }
    
    public function getActualizadoEn() {
        return $this->actualizado_en;
    }
    
    // Setters
    public function setIdCurso($id_curso) {
        $this->id_curso = $id_curso;
    }
    
    public function setIdInstructor($id_instructor) {
        $this->id_instructor = $id_instructor;
    }
    
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }
    
    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
    
    public function setDuracion($duracion) {
        $this->duracion = $duracion;
    }
    
    public function setModalidad($modalidad) {
        $this->modalidad = $modalidad;
    }
    
    public function setPrecio($precio) {
        $this->precio = $precio;
    }
    
    public function setFechaInicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }
    
    public function setCupos($cupos) {
        $this->cupos = $cupos;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function setCreadoEn($creado_en) {
        $this->creado_en = $creado_en;
    }
    
    public function setActualizadoEn($actualizado_en) {
        $this->actualizado_en = $actualizado_en;
    }
}