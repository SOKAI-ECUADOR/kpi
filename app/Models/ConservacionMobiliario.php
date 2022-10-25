<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConservacionMobiliario extends Model
{
    
    protected $table="conservacion_mobiliario";
    protected $primaryKey = "id_conservacion";
    protected $fillable=['descripcion_conservacion', 'id_empresa'];
}