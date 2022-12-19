<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaActivoUnidad extends Model
{
        
    protected $table="acta_activo_unidad";

    
    protected $fillable = [

        'nombre',
        'user_id',
    ];

    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
