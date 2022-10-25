<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoBodegaLotes extends Model
{
    protected $table = "producto_bodega_lotes";
    protected $primaryKey = 'id_producto_bodega_lotes';
    protected $fillable = ['nombre', 'cantidad_original', 'cantidad_real', 'fecha', 'fcrea', 'fmodifica', 'id_producto', 'id_producto_bodega', 'id_bodega_ingreso', 'id_bodega_ingreso_detalle'];
}
