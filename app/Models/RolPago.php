<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolPago extends Model
{
    protected $table="rol_pago";
    protected $primaryKey = "id_rol_pago";
    protected $fillable=['primer_nombre', 'cantidad','sueldo', 'ingreso1', 'ingreso2', 'ingreso3', 'ingreso4', 'ingreso5','ingreso6','total_ingreso','egreso1', 'egreso2', 'egreso3', 'egreso4', 'egreso5','egreso6','iess','total_egreso','id_departamento'];
}
