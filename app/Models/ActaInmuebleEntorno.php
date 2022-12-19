<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ActaInmuebleEntorno extends Model
{
        
    protected $table="acta_inmueble_entorno";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [
        'propiedades',
        'acta_inmueble_id',
        'user_id',
    ];

    protected $casts = [
        'fcrea' => 'datetime:Y-m-d H:i:s',
        'fmodifica' => 'datetime:Y-m-d H:i:s',
        'propiedades' => 'array'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
}
