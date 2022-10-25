<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaTransferenciaDetalle extends Model
{
    protected $table = "bodega_transferencia_detalle";
    protected $primaryKey = 'id_bodega_transferencia_detalle';
    protected $fillable = ['cant_env', 'cant_recib', 'costo_unitario', 'costo_total', 'id_producto', 'id_proyecto', 'id_bodega_transferencia'];

}
