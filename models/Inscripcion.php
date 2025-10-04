<?php
class Inscripcion extends BaseModel {
    protected $id_inscripcion;
    protected $id_curso;
    protected $id_estudiante;
    protected $fecha_inscrito;
    
    // Getters
    public function getIdInscripcion() {
        return $this->id_inscripcion;
    }
    
    public function getIdCurso() {
        return $this->id_curso;
    }
    
    public function getIdEstudiante() {
        return $this->id_estudiante;
    }
    
    public function getFechaInscrito() {
        return $this->fecha_inscrito;
    }
    
    // Setters
    public function setIdInscripcion($id_inscripcion) {
        $this->id_inscripcion = $id_inscripcion;
    }
    
    public function setIdCurso($id_curso) {
        $this->id_curso = $id_curso;
    }
    
    public function setIdEstudiante($id_estudiante) {
        $this->id_estudiante = $id_estudiante;
    }
    
    public function setFechaInscrito($fecha_inscrito) {
        $this->fecha_inscrito = $fecha_inscrito;
    }
}