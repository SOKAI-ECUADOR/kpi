<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoIngrediente extends Model
{
    protected $table = "proceso_ingrediente";
    protected $primaryKey = 'id_proceso_ingrediente';
    protected $fillable = ['cantidad_orden', 'cantidad_produccion', 'cantidad_liquidacion', 'costo_unitario_orden', 'costo_unitario_produccion', 'costo_unitario_liquidacion', 'id_producto', 'id_proceso_produccion', 'id_bodega', 'id_producto_bodega', 'id_bodega_egreso_detalle_orden', 'id_bodega_egreso_detalle_produccion', 'id_bodega_egreso_detalle_liquidacion'];
}
