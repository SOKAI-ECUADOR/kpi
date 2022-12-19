<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaCargaArchivo extends Model
{
        
    protected $table="acta_carga_archivo";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'nombre_archivo',
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
