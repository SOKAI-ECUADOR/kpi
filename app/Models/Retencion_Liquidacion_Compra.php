<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retencion_Liquidacion_Compra extends Model
{
    //
    protected $table="retencion_liquidacion_compra";
    protected $primaryKey='id_retencion_liquidacion_compra';
    protected $fillable = ['id_liquidacion_compra', 'id_retencion_iva', 'id_retencion_renta', 'porcentajeiva', 'cantidadiva', 'baserenta', 'porcentajerenta', 'cantidadrenta', 'fcrea', 'fmodifica'];
}
