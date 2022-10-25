<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipoproducto extends Model
{
    //
    protected $table="tipo_producto";
    protected $primaryKey = "id_tipo_producto";
    protected $fillable=['codigo', 'nombre', 'utilidad', 'id_linea_producto', 'id_empresa'];

}
