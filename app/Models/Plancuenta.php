<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plancuenta extends Model
{
    //
    protected $table="plan_cuentas";
    protected $primaryKey = "id_plan_cuentas";
    protected $fillable=[ 'id_empresa', 'codcta','num_cuenta', 'nomcta', 'id_moneda', 'refcon', 'bansel', 'id_grupo','id_moneda','id_banco','id_empresa'];
   
    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
