<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcesoProduccion extends Model
{
    protected $table="proceso_produccion";
    protected $primaryKey ='id_proceso_produccion';
    protected $fillable =['num_orden', 'detalle', 'estado', 'fecha_inicio', 'fecha_proceso', 'fecha_fin', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'id_empresa', 'id_establecimiento', 'id_proyecto' ];
}