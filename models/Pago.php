<?php
class Pago extends BaseModel {
    protected $id_pago;
    protected $id_inscripcion;
    protected $proveedor;
    protected $estado;
    protected $monto;
    protected $moneda;
    protected $id_transaccion;
    protected $url_recibo;
    protected $creado_en;
    protected $actualizado_en;
    
    // Getters
    public function getIdPago() {
        return $this->id_pago;
    }
    
    public function getIdInscripcion() {
        return $this->id_inscripcion;
    }
    
    public function getProveedor() {
        return $this->proveedor;
    }
    
    public function getEstado() {
        return $this->estado;
    }
    
    public function getMonto() {
        return $this->monto;
    }
    
    public function getMoneda() {
        return $this->moneda;
    }
    
    public function getIdTransaccion() {
        return $this->id_transaccion;
    }
    
    public function getUrlRecibo() {
        return $this->url_recibo;
    }
    
    public function getCreadoEn() {
        return $this->creado_en;
    }
    
    public function getActualizadoEn() {
        return $this->actualizado_en;
    }
    
    // Setters
    public function setIdPago($id_pago) {
        $this->id_pago = $id_pago;
    }
    
    public function setIdInscripcion($id_inscripcion) {
        $this->id_inscripcion = $id_inscripcion;
    }
    
    public function setProveedor($proveedor) {
        $this->proveedor = $proveedor;
    }
    
    public function setEstado($estado) {
        $this->estado = $estado;
    }
    
    public function setMonto($monto) {
        $this->monto = $monto;
    }
    
    public function setMoneda($moneda) {
        $this->moneda = $moneda;
    }
    
    public function setIdTransaccion($id_transaccion) {
        $this->id_transaccion = $id_transaccion;
    }
    
    public function setUrlRecibo($url_recibo) {
        $this->url_recibo = $url_recibo;
    }
    
    public function setCreadoEn($creado_en) {
        $this->creado_en = $creado_en;
    }
    
    public function setActualizadoEn($actualizado_en) {
        $this->actualizado_en = $actualizado_en;
    }
}