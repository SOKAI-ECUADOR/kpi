<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaEgresoDetalle extends Model
{
    //
    protected $table="bodega_egreso_detalle";
    protected $primaryKey='id_bodega_egreso_detalle';
    protected $fillable = ['cantidad', 'costo_unitario', 'costo_total', 'id_bodega_egreso' , 'id_producto', 'id_proyecto', 'id_detalle', 'id_detalle_nota_credito', 'id_detalle_nota_credito_compra', 'id_bodega_transferencia_detalle', 'id_proceso_ingrediente'];
}
