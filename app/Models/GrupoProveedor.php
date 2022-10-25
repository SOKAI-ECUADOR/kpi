<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoProveedor extends Model
{
    //
    protected $table="grupo_proveedor";
    protected $primaryKey = 'id_grupoprov';
    protected $fillable =['nombre_grupoprov','importador','id_plan_cuentas','id_plan_cuentas_descuento','id_plan_cuentas_anticipo','id_empresa'];
}