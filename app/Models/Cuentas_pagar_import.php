<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuentas_pagar_import extends Model{
    protected $table="ctas_pagar";
    protected $primaryKey='id_ctaspagar';
    protected $fillable = ['num_cuota', 'fecha_pago', 'periodo_pagos', 'referencias', 'valor_cuota', 'descuento', 'valor_pagado', 'estado', 'tipo', 'id_proveedor', 'id_factura_compra', 'id_empresa'];
}
