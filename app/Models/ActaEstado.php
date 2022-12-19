<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActaEstado extends Model
{
    
    protected $table="acta_estado";

    
    protected $fillable = [

        'nombre',
        'origen',
        'user_id',
    ];
    
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
