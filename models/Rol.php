<?php

class Rol extends BaseModel {
    protected $id_rol;
    protected $codigo;
    protected $nombre;
    
    // Getters
    public function getIdRol() {
        return $this->id_rol;
    }
    
    public function getCodigo() {
        return $this->codigo;
    }
    
    public function getNombre() {
        return $this->nombre;
    }
    
    // Setters
    public function setIdRol($id_rol) {
        $this->id_rol = $id_rol;
    }
    
    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
}