<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obligacion extends Model
{
    //
    protected $table = "obligacion";
    protected $primaryKey = "id_obligacion";
    protected $fillable = [
        'descripcion_obligacion', 'fcrea', 'fmodifica'
    ];
}
