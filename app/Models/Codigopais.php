<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Codigopais extends Model
{
    //
    protected $table="codigo_pais";
    protected $primaryKey = 'id_codigo_pais';
    protected $fillable =['codigo_ISO_alpha_numerico', 'codigo_ISO_alpha_2', 'nombre_pais'];
}
