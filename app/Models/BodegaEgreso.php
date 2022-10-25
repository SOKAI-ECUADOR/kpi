<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaEgreso extends Model
{
    protected $table = "bodega_egreso";
    protected $primaryKey = 'id_bodega_egreso';
    protected $fillable = ['num_egreso', 'fecha_egreso', 'tipo_egreso', 'observ_egreso', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_proyecto', 'id_bodega', 'id_empresa', 'id_factura', 'id_nota_credito', 'id_factura_compra', 'id_nota_credito_compra', 'id_bodega_transferencia', 'id_proceso_produccion', 'contabilidad'];

}


