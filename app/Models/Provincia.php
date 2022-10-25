<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    //
    protected $table="provincia";
    protected $primaryKey='id_provincia';
    protected $fillable = [
        'codigo',
        'nombre'
    ];
}


