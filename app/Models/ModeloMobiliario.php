<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeloMobiliario extends Model
{
    
    protected $table="modelo_mobiliario";
    protected $primaryKey = "id_modelo";
    protected $fillable=['nombre_modelo', 'id_empresa'];
}