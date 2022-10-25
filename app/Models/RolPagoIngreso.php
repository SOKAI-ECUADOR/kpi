<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolPagoIngreso extends Model
{
    protected $table="rol_pago_ingreso";
    protected $primaryKey = "id_rol_pago_ingreso";
    protected $fillable=['primer_nombre', 'cantidad', 'ingreso1', 'ingreso2', 'ingreso3', 'ingreso4', 'ingreso5','ingreso6','total_ingreso','id_departamento'];
}
