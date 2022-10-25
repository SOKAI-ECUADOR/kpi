<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad_medida extends Model
{
    protected $table="unidad_medida";
    protected $primaryKey = "id_unidad_medida";
    protected $fillable=['nombre', 'id_tipo_medida'];
}
