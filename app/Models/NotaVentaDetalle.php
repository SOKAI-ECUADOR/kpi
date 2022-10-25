<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaVentaDetalle extends Model
{
    protected $table="detalle_nota_venta";
    protected $primaryKey = "id_detalle_nota_venta";
    protected $fillable =['nombre', 'cantidad', 'precio', 'descuento', '%descuento', 'total', 'p_descuento', 'id_iva', 'id_ice', 'irbpnr','tiempo_entrega', 'cpc', 'fcrea', 'fmodifica', 'id_producto', 'id_nota_credito','id_nota_venta'];
}
