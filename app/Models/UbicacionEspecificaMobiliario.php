<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionEspecificaMobiliario extends Model
{
    
    protected $table="ubicacion_especifica_mobiliario";
    protected $primaryKey = "id_ubicacion_especifica";
    protected $fillable=['descripcion_ubicacion_especifica', 'id_ubicacion_general', 'id_empresa'];
}