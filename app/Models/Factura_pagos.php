<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura_pagos extends Model
{
    protected $table = "factura_pagos";
    protected $primaryKey = 'id_facturas_pagos';
    protected $fillable = ['forma-pago', 'total', 'plazo', 'unidad_tiempo', 'estado', 'fecha', 'tiempos_pagos', 'numero_transaccion','id_forma_pagos', 'id_banco', 'id_plan_cuentas', 'id_factura'];
}
