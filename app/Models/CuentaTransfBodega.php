<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaTransfBodega extends Model
{
    //
    protected $table="cuenta_bodega_transferencia";
    protected $primaryKey='id_cuenta_bodega_transferencia';
    protected $fillable = ['cod_cuenta', 'nombre_cuenta', 'id_plan_cuentas', 'id_empresa'];
}
