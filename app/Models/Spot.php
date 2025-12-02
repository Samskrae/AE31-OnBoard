<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $fillable = [
        'nombre',
        'lat',
        'lon',
        'descripcion',
        'nivel',
        'imagen',
    ];
}
