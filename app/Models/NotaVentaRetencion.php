<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaVentaRetencion extends Model
{
    //
    protected $table="retencion_nota_venta";
    protected $primaryKey = 'id_retencion_nota_venta';
    protected $fillable=['id_nota_venta', 'id_retencion_iva', 'id_retencion_renta', 'fcrea', 'fmodifica'];
}
