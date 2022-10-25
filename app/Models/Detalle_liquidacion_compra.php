<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_liquidacion_compra extends Model
{
    //
    protected $table="detalle_liquidacion_compra";
    protected $primaryKey ='id_detalle_liquidacion_compra';
    protected $fillable =['nombre', 'cantidad', 'precio', 'descuento', '%descuento', 'total', 'iva', 'ice', 'irbpnr', 'fcrea', 'fmodifica', 'id_producto', 'id_liquidacion_compra'];
}
