<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalidadGrafico2 extends Model
{
    use HasFactory;

    protected $table = 'calidad_grafico2';
    protected $fillable = ['dato', 'meta'];
}
