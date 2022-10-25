<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    protected $table ="establecimiento"; 
    protected $primaryKey = "id_establecimiento";
    protected $fillable =['nombre', 'codigo', 'urlweb', 'nombre_comercial', 'direccion', 'estado', 'id_empresa'];
}
