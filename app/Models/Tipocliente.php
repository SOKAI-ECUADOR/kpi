<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipocliente extends Model
{
    //
    protected $table="tipo_cliente";
    protected $primaryKey ="id_tipo_cliente";
    protected $fillable =['descripcion_tipo_cliente','fcrea','fmodifica','ucrea','umodifica'];

}
