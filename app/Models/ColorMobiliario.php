<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorMobiliario extends Model
{
    
    protected $table="color_mobiliario";
    protected $primaryKey = "id_color";
    protected $fillable=['nombre_color', 'id_empresa'];
}