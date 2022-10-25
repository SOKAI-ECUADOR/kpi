<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_medida extends Model
{
    protected $table="tipo_medida";
    protected $primaryKey = "id_tipo_medida";
    protected $fillable=['nombre'];
}
