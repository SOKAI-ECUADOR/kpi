<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcaMobiliario extends Model
{
    
    protected $table="marca_mobiliario";
    protected $primaryKey = "id_marca";
    protected $fillable=['nombre_marca', 'id_empresa'];
}