<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolPagoEgreso extends Model
{
    protected $table="rol_pago_egreso";
    protected $primaryKey = "id_rol_pago_egreso";
    protected $fillable=['primer_nombre', 'cantidad', 'egreso1', 'egreso2', 'egreso3', 'egreso4', 'egreso5','egreso6','total_egreso','id_departamento'];
}
