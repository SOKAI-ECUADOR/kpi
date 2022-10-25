<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagocompra extends Model
{
    //
    protected $table="pago_fact_compra";
    protected $primaryKey = "id_pagfactcompra";
    protected $fillable=['metodo_pago', 'tarjeta', 'cantidad', 'comentario', 'id_factcompra', 'id_banco', 'id_empresa', 'id_ctacontable' ];
}
