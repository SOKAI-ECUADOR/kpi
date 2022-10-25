<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivoArea extends Model
{
    //
    protected $table="activo_fijo_area";
    protected $primaryKey ='id_activo_fijo_area';
    protected $fillable =['codigo','nombre','id_empresa'];
}
