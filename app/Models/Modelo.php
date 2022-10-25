<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table="modelo";
    protected $primaryKey = "id_modelo";
    protected $fillable =['nombre', 'descripcion', 'id_empresa' ];

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
