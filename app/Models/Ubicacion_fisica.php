<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion_fisica extends Model
{
    protected $table="ubicacion_fisica";
    protected $primaryKey = "id_ubicacion_fisica";
    protected $fillable =['nombre', 'descripcion', 'id_bodega', 'fcrea', 'fmodifica', 'ucrea', 'umodifica']; 
}
