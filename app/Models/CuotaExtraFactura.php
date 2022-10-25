<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuotaExtraFactura extends Model
{
    protected $table="cuota_extra_factura";
    protected $primaryKey ='id_cuota_extra_factura';
    protected $fillable =['valor_cuota','fecha_pago','estado','id_factura'];
}
