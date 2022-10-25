<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleMasiva extends Model
{
    protected $table="detalle_masiva";
    protected $primaryKey = "id_detalle_masiva";
    protected $fillable =['nombre', 'cantidad', 'precio', 'descuento', '%descuento', 'total', 'valor_sin_iva', 'p_descuento', 'id_iva', 'id_ice', 'valor_ice', 'irbpnr','tiempo_entrega', 'cpc', 'fcrea', 'fmodifica', 'id_producto', 'id_nota_credito','id_factura_masiva','id_producto_bodega', 'id_proyecto'];
}
