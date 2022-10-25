<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depreciacion extends Model
{
    //
    protected $table="depreciacion";
    protected $primaryKey ='id_depreciacion';
    protected $fillable =['codigo_depreciacion','fecha_inicio','fecha_fin','todos_activos','id_grupo_activo_fijo','id_tipo_activo_fijo','id_empresa'];
}
