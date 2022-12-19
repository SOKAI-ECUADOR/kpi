<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaInmuebleMunicipio extends Model
{
        
    protected $table="acta_inmueble_municipio";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'detalle',
        'acta_inmueble_id',
        'ano_impuesto_predial',
        'clave_catastral',
        'geo_clave',
        'ano_1_numero',
        'ano_1_valor',
        'ano_1_construccion',
        'ano_1_terreno',
        'ano_2_numero',
        'ano_2_valor',
        'ano_2_construccion',
        'ano_2_terreno',
        'user_id',
    ];


    protected $casts = [
        'fcrea' => 'datetime:Y-m-d H:i:s',
        'fmodifica' => 'datetime:Y-m-d H:i:s'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
