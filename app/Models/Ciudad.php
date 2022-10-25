<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    //
    
    protected $table="ciudad";
    protected $primaryKey ='id_ciudad';
    protected $fillable =['codigo', 'nombre' ,'id_provincia' ];
}

