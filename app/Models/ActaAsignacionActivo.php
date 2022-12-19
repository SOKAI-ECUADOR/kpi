<?php

namespace App\Models;

use App\Models\User;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Model;

class ActaAsignacionActivo extends Model
{
    
    protected $table="acta_asignacion_activo";

    const CREATED_AT = 'fcrea';
    const UPDATED_AT = 'fmodifica';

    
    protected $fillable = [

        'empresa_id',
        'acta_agencia_id',
        'acta_responsable_id',
        'periodo',
        'acta_estado_id',
        'user_id',
    ];

    protected $casts = [
        'fcrea' => 'datetime:Y-m-d H:i:s',
        'fmodifica' => 'datetime:Y-m-d H:i:s'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function empresa() {
        return $this->belongsTo(Empresa::class);
    }
}
