<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota_debito_pagos extends Model
{
    protected $table = "nota_debito_pagos";
    protected $primaryKey = 'id_nota_debito_pagos';
    protected $fillable = ['forma-pago', 'total', 'plazo', 'unidad_tiempo', 'estado', 'fecha', 'tiempos_pagos', 'numero_transaccion','id_forma_pagos', 'id_banco', 'id_plan_cuentas', 'id_nota_debito_pagos'];
}
