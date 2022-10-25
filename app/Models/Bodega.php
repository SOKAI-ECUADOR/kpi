<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = "bodega";
    protected $primaryKey = "id_bodega";
    protected $fillable = ['codigo', 'nombre', 'responsable', 'ubicacion', 'direccion', 'telefono', 'visible', 'id_plan_cuentas', 'id_establecimiento', 'id_empresa'];
}
