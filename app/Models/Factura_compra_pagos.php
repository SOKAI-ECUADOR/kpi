<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura_compra_pagos extends Model
{
    protected $table = "factura_compra_pagos";
    protected $primaryKey = 'id_factura_compra_pagos';
    protected $fillable = ['forma-pago', 'total', 'plazo', 'unidad_tiempo', 'estado', 'fecha', 'id_factura_compra'];
}
