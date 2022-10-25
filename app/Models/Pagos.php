<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    protected $table="pagos";
    protected $primaryKey = "id_pagos";
    protected $fillable=['metodo_pago', 'banco', 'tarjeta', 'cuenta_contable', 'cantidad', 'comentario', 'id_empresa'];
}
