<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    //
    protected $table="impuesto";
    protected $primaryKey = "id_imp";
    protected $fillable=['cod_imp', 'descrip_imp', 'tipo_imp','porcen_imp','id_empresa'];
}
