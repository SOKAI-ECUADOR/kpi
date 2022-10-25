<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiposustento extends Model
{
    //
    protected $table="tipo_sustento";
    protected $primaryKey = 'id_sustento';
    protected $fillable =['cod_sustento', 'descrip_sustento','id_empresa'];
}
