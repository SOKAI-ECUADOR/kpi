<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoFactura extends Model
{
    //
    protected $table="producto_importacion";
    protected $primaryKey = 'id_prodimp';
    protected $fillable=[ 'nombre', 'cantidad', 'precio', 'descuento', 'p_descuento', 'total', 'id_importacion', 'id_producto', 'iva'];
}
