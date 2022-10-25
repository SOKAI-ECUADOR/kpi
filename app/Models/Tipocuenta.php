<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipocuenta extends Model
{
    //
    protected $table="tipocuenta";
    protected $primaryKey = "id";
    protected $fillable=['id', 'nombreCuenta', 'createdAt', 'updatedAt', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
