<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    //
    protected $table="grupo";
    protected $primarykey = "id";
    protected $fillable=['id', 'nombGrupo', 'createdAt', 'updatedAt', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
