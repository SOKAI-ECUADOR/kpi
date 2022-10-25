<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ctacontable extends Model
{
    //
    protected $table="ctacontable";
    protected $primaryKey = "id";
    protected $fillable =['id', 'nroCta'];
}
