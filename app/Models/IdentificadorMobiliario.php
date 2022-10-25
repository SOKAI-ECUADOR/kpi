<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdentificadorMobiliario extends Model
{
    
    protected $table="identificador_mobiliario";
    protected $primaryKey = "id_identificador";
    protected $fillable=['descripcion_identificador', 'id_empresa'];
}