<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CuentaIngresoBodega extends Model
{
    //
    protected $table="cuenta_bodega_ingreso";
    protected $primaryKey='id_cuenta_bodega_ingreso';
    protected $fillable = ['cod_cuenta', 'nombre_cuenta', 'id_plan_cuentas', 'id_empresa'];
}
