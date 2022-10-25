<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaPagosMasiva extends Model
{
    protected $table = "factura_pagos_masiva";
    protected $primaryKey = 'id_factura_pagos_masiva';
    protected $fillable = ['total', 'plazo', 'unidad_tiempo', 'estado', 'fecha', 'tiempos_pagos', 'numero_transaccion', 'fecha_pago', 'anticipo', 'id_factura_masiva', 'id_forma_pagos', 'id_banco', 'id_plan_cuentas'];
}
