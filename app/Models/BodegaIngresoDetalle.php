<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaIngresoDetalle extends Model
{
    //
    protected $table="bodega_ingreso_detalle";
    protected $primaryKey='id_bodega_ingreso_detalle';
    protected $fillable = ['cantidad', 'costo_unitario', 'costo_total', 'id_bodega_ingreso' , 'id_producto', 'id_proyecto', 'id_detalle_factura_compra', 'id_detalle_nota_credito', 'id_detalle_nota_credito_compra', 'id_producto_importacion', 'id_bodega_transferencia_detalle', 'id_proceso_producto'];
}

