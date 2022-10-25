<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    protected $table="presentacion";
    protected $primaryKey = "id_presentacion";
    protected $fillable =['nombre', 'descripcion', 'id_empresa'];

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
