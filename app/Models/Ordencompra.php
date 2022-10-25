<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordencompra extends Model
{
    //
    protected $table="orden_compra";
    protected $primaryKey ='id_ordencompra';
    protected $fillable =['f_emision', 'f_validez', 'forma_pago', 'subtotal_sinimp', 'subtotal_iva12', 'valor_12', 'subtotal_iva0', 'sin_imp', 'total_descuento', 'total_orden', 'observacion', 'id_proveedor', 'id_empresa'];
}
