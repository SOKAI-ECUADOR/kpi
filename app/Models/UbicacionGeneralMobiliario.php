<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UbicacionGeneralMobiliario extends Model
{
    
    protected $table="ubicacion_general_mobiliario";
    protected $primaryKey = "id_ubicacion_general";
    protected $fillable=['descripcion_ubicacion_general', 'id_empresa'];
}