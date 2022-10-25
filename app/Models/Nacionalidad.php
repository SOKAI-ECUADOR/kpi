<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nacionalidad extends Model
{
    protected $table = "nacionalidad";
    protected $primaryKey = "id_nacionalidad";
    protected $fillable = ['PAIS_NAC', 'GENTILICIO_NAC', 'ISO_NAC'];
}