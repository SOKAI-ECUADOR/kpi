<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoBodega extends Model
{
    protected $table = "producto_bodega";
    protected $primaryKey = 'id_producto_bodega';
    protected $fillable = ['cantidad', 'costo_unitario', 'costo_total', 'id_producto', 'id_bodega', 'id_empresa'];
}
