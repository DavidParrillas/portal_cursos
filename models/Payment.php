<?php
// Modelo de pagos
class Payment extends BaseModel {
    public $id_pago;
    public $id_inscripcion;
    public $proveedor;
    public $transaction_id;
    public $monto;
    public $moneda;
    public $estado;
    public $fecha_pago;
    public $comprobante_url;
}
