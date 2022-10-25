<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cta extends Model
{
    //
    protected $table="plan_cuentas";
    protected $primaryKey = 'id_ctas';
    protected $fillable =['id_empresa', 'codcta', 'nomcta', 'id_moneda', 'refcon', 'bansel', 'fcrea', 'fmodifica', 'id_grupo', 'id_user', 'fecult'];
}

