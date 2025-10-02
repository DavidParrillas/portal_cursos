<?php
class Resena {
    private $id_resena;
    private $id_curso;
    private $id_estudiante;
    private $calificacion;
    private $comentario;
    private $creado_en;
    
    // Getters
    public function getIdResena() {
        return $this->id_resena;
    }
    
    public function getIdCurso() {
        return $this->id_curso;
    }
    
    public function getIdEstudiante() {
        return $this->id_estudiante;
    }
    
    public function getCalificacion() {
        return $this->calificacion;
    }
    
    public function getComentario() {
        return $this->comentario;
    }
    
    public function getCreadoEn() {
        return $this->creado_en;
    }
    
    // Setters
    public function setIdResena($id_resena) {
        $this->id_resena = $id_resena;
    }
    
    public function setIdCurso($id_curso) {
        $this->id_curso = $id_curso;
    }
    
    public function setIdEstudiante($id_estudiante) {
        $this->id_estudiante = $id_estudiante;
    }
    
    public function setCalificacion($calificacion) {
        $this->calificacion = $calificacion;
    }
    
    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }
    
    public function setCreadoEn($creado_en) {
        $this->creado_en = $creado_en;
    }
}