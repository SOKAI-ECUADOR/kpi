<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleGuiaRemisionNotaVenta extends Model
{
    protected $table="detalle_guia_nota_venta";
    protected $primaryKey = "id_detalle_guia_nota_venta";
    protected $fillable=['codigo_interno', 'codigo_adicional', 'descripcion', 'cantidad', 'id_producto', 'id_guia_remision_nota_venta'];
}
