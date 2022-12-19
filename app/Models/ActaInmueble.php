<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaInmueble extends Model
{
        
    protected $table="acta_inmueble";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'nombre',
        'numero_interno',
        'institucion',
        'finalidad_avaluo',
        'agencia_oficina',
        'nombre_cliente',
        'direccion',
        'fecha_inspeccion',
        'tipo_bien_descripcion',
        'tipo_bien_descripcion_detalle',
        'ubicacion',
        'acta_provincia_id',
        'acta_canton_id',
        'acta_ciudad_id',
        'acta_parroquia_id',
        'ubicacion_barrio',
        'ubicacion_manzana',
        'ubicacion_lote',
        'ubicacion_latitud',
        'ubicacion_longitud',
        'acta_estado_id',
        'user_id',
        'empresa_id',
    ];


    protected $casts = [
        'fcrea' => 'datetime:Y-m-d H:i:s',
        'fmodifica' => 'datetime:Y-m-d H:i:s'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
