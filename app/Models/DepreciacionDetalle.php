<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepreciacionDetalle extends Model
{
    //
    protected $table="depreciacion_detalle";
    protected $primaryKey ='id_depreciacion_detalle';
    protected $fillable =['nombre_activo_fijo','fecha_activo','grupo_activo','tipo_activo','valor_bien','id_depreciacion','id_activo_fijo'];
}
