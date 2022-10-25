<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table="marca";
    protected $primaryKey = "id_marca";
    protected $fillable =['nombre', 'descripcion', 'id_empresa', 'created_by', 'updated_by'];

    public function campoadicionales()
    {
        return $this->hasMany('App\Campoadicional');
    }
}
