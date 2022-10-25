<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaGuiaDeRemision extends Model
{
    protected $table = "guia_remision";
    protected $primaryKey = 'id_guia';
    protected $fillable = [
        'razon_social',
        'tipo_identificacion',
        'identificacion',
        'fecha_inicio',
        'fecha_fin',
        'placa',
        'doc_aduanero',
        'motivo_translado',
        'cod_establecimiento',
        'ruta',
        'cod_sustento',
        'num_aut_sustento',
        'fcrea',
        'fmodifica',
        'id_empresa',
        'id_factura',
    ];
}
