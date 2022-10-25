<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidaction_compra_pagos extends Model
{
    protected $table = "liquidacion_compra_pagos";
    protected $primaryKey = 'id_liquidacion_compra_pagos';
    protected $fillable = [ 'total', 'plazo', 'unidad_tiempo', 'estado', 'fecha', 'id_liquidacion_compra'];
}
