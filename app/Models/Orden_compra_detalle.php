<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden_compra_detalle extends Model
{
    protected $table = "orden_compra_detalle";
    protected $primaryKey = 'id_orden_compra_detalle';
    protected $fillable = ['nombre', 'cantidad', 'precio', 'p_descuento', 'descuento', 'total', 'id_iva', 'id_ice', 'valor_ice', 'id_producto', 'id_orden_compra', 'id_proyecto'];
}
