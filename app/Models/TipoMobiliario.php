<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMobiliario extends Model
{
    
    protected $table="tipo_mobiliario";
    protected $primaryKey = "id_tipo";
    protected $fillable=['descripcion_tipo', 'id_empresa'];
}