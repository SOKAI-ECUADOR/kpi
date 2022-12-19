<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaInmuebleAvaluo extends Model
{
        
    protected $table="acta_inmueble_avaluo";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'detalle',
        'acta_inmueble_id',
        'valor_reposicion_terreno',
        'valor_actual_terreno',
        'valor_realizacion_terreno',
        'valor_reposicion_construccion',
        'valor_actual_construccion',
        'valor_realizacion_construccion',
        'valor_total_reposicion',
        'valor_total_actual',
        'valor_total_realizacion',
    ];


    protected $casts = [
        'fcrea' => 'datetime:Y-m-d H:i:s',
        'fmodifica' => 'datetime:Y-m-d H:i:s'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
