<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_factura_compra extends Model
{
    protected $table="detalle_factura_compra";
    protected $primaryKey ='id_detalle_factura_compra';
    protected $fillable =['nombre', 'nomb', 'cantidad', 'precio', 'descuento', '%descuento', 'total', 'iva', 'ice', 'irbpnr', 'fcrea', 'fmodifica', 'id_producto', 'id_factura'];
}
