<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialMobiliario extends Model
{
    
    protected $table="material_mobiliario";
    protected $primaryKey = "id_material";
    protected $fillable=['descripcion_material', 'id_empresa'];
}