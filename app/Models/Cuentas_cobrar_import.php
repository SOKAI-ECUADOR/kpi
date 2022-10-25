<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cuentas_cobrar_import extends Model{
    protected $table="ctas_cobrar";
    protected $primaryKey='id_ctascobrar';
    protected $fillable = ['num_cuota', 'fecha_pago','fecha_factura', 'periodo_pagos', 'referencias', 'valor_cuota', 'descuento', 'valor_pagado', 'estado', 'tipo', 'id_factura', 'id_cliente', 'id_empresa'];
}
