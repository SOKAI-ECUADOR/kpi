<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $table = "cliente";
    protected $primaryKey = "id_cliente";
    protected $fillable = [
        'codigo', 'nombre', 'nombre_adicional', 'identificacion', 'direccion', 'email', 'telefono', 'contacto', 'estado',  'comentario', 'descuento', 'num_pago', 'tipo_identificacion',
        'id_codigo_pais', 'grupo_tributario', 'parte_relacionada', 'obligado_contabilidad', 'lista_precios', 'limite_credito', 'regimen_contribuyente', 'id_forma_pagos','id_grupo_cliente', 'id_empresa', 'id_tipo_cliente', 'id_vendedor', 'id_plan_cuentas', 'id_provincia', 'id_cuidad', 'id_parroquia','created_by', 'updated_by'
        
    ];

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
