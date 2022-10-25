<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoMobiliario extends Model
{
    
    protected $table="mantenimiento_mobiliario";
    protected $primaryKey = "id_mantenimiento";
    protected $fillable=['descripcion_mantenimiento', 'id_empresa'];
}