<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivoTipo extends Model
{
    //
    protected $table="activo_fijo_tipo";
    protected $primaryKey ='id_activo_fijo_tipo';
    protected $fillable =['codigo','nombre','id_empresa'];
}
