<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    protected $table="iva";
    protected $primaryKey = "id_iva";
    protected $fillable =['nombre'];
}
