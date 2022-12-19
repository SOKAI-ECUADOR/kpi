<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaInmuebleEscritura extends Model
{
        
    protected $table="acta_inmueble_escritura";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'detalle',
        'acta_inmueble_id',
        'notaria',
        'acta_canton_id',
        'fecha_escrituracion_registro',
        'superficie',
        'cuantia',
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
