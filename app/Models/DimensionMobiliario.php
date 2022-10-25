<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimensionMobiliario extends Model
{
    
    protected $table="dimension_mobiliario";
    protected $primaryKey = "id_dimension";
    protected $fillable=['descripcion_dimension', 'id_empresa'];
}