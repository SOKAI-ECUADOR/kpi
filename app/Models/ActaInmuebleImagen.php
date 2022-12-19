<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaInmuebleImagen extends Model
{
        
    protected $table="acta_inmueble_imagen";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'titulo',
        'nombre',
        'acta_inmueble_imagen_tipo_id',
        'archivo',
        'user_id',
        'empresa_id',
        'acta_inmueble_id'
    ];


    protected $casts = [
        'fcrea' => 'datetime:Y-m-d H:i:s',
        'fmodifica' => 'datetime:Y-m-d H:i:s'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
