<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moneda extends Model
{
    //
    protected $table="moneda";
    protected $primarykey = "id";
    protected $fillable=['id', 'nombMoneda', 'valor', 'created_at', 'updated_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'];
}
