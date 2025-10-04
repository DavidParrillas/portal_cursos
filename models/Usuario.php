<?php
// Modelo para la tabla 'usuarios'
class Usuario extends BaseModel {
    protected $id_usuario;
    protected $nombre_completo;
    protected $correo;
    protected $contrasena_hash;
    protected $estado;
    protected $creado_en;
    protected $actualizado_en;

    // Getters
    public function getIdUsuario() {
        return $this->id_usuario;
    }
    
    public function getNombreCompleto() {
        return $this->nombre_completo;
    }
    
    public function getCorreo() {
        return $this->correo;
    }
    
    public function getContrasenaHash() {
        return $this->contrasena_hash;
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
    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }
    
    public function setNombreCompleto($nombre_completo) {
        $this->nombre_completo = $nombre_completo;
    }
    
    public function setCorreo($correo) {
        $this->correo = $correo;
    }
    
    public function setContrasenaHash($contrasena_hash) {
        $this->contrasena_hash = $contrasena_hash;
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
