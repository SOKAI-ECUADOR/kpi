<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ActaInmuebleFlujo extends Model
{
        
    protected $table="acta_inmueble_flujo";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [
        'detalle',
        'acta_inmueble_id',
        'user_id',
    ];

    protected $casts = [
        'fcrea' => 'datetime:Y-m-d H:i:s',
        'fmodifica' => 'datetime:Y-m-d H:i:s',
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
}
