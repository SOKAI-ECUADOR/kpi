<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaActivoTipo extends Model
{
        
    protected $table="acta_activo_tipo";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'nombre',
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
