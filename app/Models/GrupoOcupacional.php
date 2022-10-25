<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoOcupacional extends Model
{
    //
    protected $table="grupo_ocupacional";
    protected $primaryKey='id_grupo';
    protected $fillable = ['grup_nombre', 'grup_estado'];
}
