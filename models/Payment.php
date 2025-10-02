<?php
// Modelo de pagos
class Payment extends BaseModel {
    public $id_pago;
    public $id_inscripcion;
    public $proveedor;
    public $id_transaccion;
    public $monto;
    public $moneda;
    public $estado;
    public $url_recibo;
    public $creado_en;
    public $actualizado_en;
}
