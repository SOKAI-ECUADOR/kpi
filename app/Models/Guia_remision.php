<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guia_remision extends Model
{
    protected $table="guia_remision";
    protected $primaryKey = "id_guia";
    protected $fillable=['razon_social', 'tipo_identificacion', 'identificacion', 'fecha_inicio', 'fecha_fin', 'placa', 'doc_aduanero', 'motivo_translado', 'cod_establecimiento', 'ruta', 'cod_sustento', 'num_aut_sustento', 'fcrea', 'fmodifica', 'id_empresa', 'id_factura', 'id_cliente', 'id_user', 'id_punto_emision', 'id_establecimiento'];
}
