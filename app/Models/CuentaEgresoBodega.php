<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaEgresoBodega extends Model
{
    //
    protected $table="cuenta_bodega_egreso";
    protected $primaryKey='id_cuenta_bodega_egreso';
    protected $fillable = ['cod_cuenta', 'nombre_cuenta', 'id_plan_cuentas', 'id_empresa'];
}
