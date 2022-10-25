<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    //
    protected $table="caja";
    protected $primaryKey ='id_caja';
    protected $fillable =['descrip_caja', 'id_moneda', 'id_empresa', 'id_plan_cuentas'];
}
