<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaProduccion extends Model
{
    //
    protected $table="cuenta_produccion";
    protected $primaryKey='id_cuenta_produccion';
    protected $fillable = ['cod_cuenta', 'nombre_cuenta', 'id_plan_cuentas', 'id_empresa'];
}
