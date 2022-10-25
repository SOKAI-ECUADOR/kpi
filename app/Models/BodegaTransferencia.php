<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodegaTransferencia extends Model
{
    protected $table = "bodega_transferencia";
    protected $primaryKey = 'id_bodega_transferencia';
    protected $fillable = ['num_trans', 'estado', 'f_iniciacion', 'f_finalizacion', 'motivo_trans', 'observ_trans', 'transporte', 'marcav', 'placasv', 'colorv', 'transportista', 'fcrea', 'fmodifica', 'ucrea', 'umodifica', 'emisor_trans', 'receptor_trans', 'id_proyecto', 'id_empresa'];
}
