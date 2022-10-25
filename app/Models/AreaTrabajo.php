<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaTrabajo extends Model
{
    protected $table="area_trabajo";
    protected $primaryKey ='id_area';
    protected $fillable =['are_nombre', 'iddepa' ];
}