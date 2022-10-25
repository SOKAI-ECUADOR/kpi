<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lineaproducto extends Model
{
    //
    protected $table="linea_producto";
    protected $primaryKey = "id_linea_producto";
    protected $fillable=['codigo', 'nombre', 'id_plan_cuentas_compras_iva', 'id_plan_cuentas_compras_iva_0', 'id_plan_cuentas_ventas_iva', 'id_plan_cuentas_ventas_iva_0', 'id_plan_cuentas_costo', 'id_empresa'];
   
   
    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
} 
