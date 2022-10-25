<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaIngreso extends Model
{
    protected $table = "bodega_ingreso";
    protected $primaryKey = 'id_bodega_ingreso';
    protected $fillable = ['num_ingreso', 'fecha_ingreso', 'tipo_ingreso', 'observ_ingreso', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_proyecto', 'id_bodega', 'id_empresa','id_importacion', 'id_factura', 'id_nota_credito', 'id_factura_compra', 'id_nota_credito_compra', 'id_bodega_transferencia', 'id_proceso_produccion', 'contabilidad'];

}

