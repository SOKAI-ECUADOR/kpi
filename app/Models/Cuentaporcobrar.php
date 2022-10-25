<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cuentaporcobrar extends Model{
    protected $table="ctas_cobrar";
    protected $primaryKey='id_ctascobrar';
    protected $fillable = ['unidad_tiempo', 'nro_cuota', 'cuotas_totales', 'cuotas_pagadas', 'forma_pago', 'banco', 'nro_tarjeta', 'cta_contable', 'monto', 'abono', 'saldo', 'descuento', 'estado', 'fcrea', 'fmodifica', 'freguistro', 'fvencimiento', 'ucrea', 'umodifica', 'id_cliente', 'id_factura', 'created_by', 'updated_by'];
}
