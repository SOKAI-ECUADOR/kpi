<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    protected $table="parroquia";
    protected $primaryKey ='id_parroquia';
    protected $fillable =['codigo', 'nombre', 'id_provincia' ];
}