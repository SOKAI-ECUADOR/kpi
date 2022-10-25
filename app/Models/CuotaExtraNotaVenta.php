<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuotaExtraNotaVenta extends Model
{
    protected $table="cuota_extra_nota_venta";
    protected $primaryKey ='id_cuota_extra_nota_venta';
    protected $fillable =['valor_cuota','fecha_pago','estado','id_nota_venta'];
}
