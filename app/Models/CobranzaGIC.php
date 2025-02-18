<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobranzaGIC extends Model
{
    use HasFactory;
    protected $table = 'cobranza_gic'; // Nombre de la tabla en la base de datos
    protected $fillable = [
        'semana',
        'en_tiempo',
        'rango1',
        'rango2',
        'rango3',
        'rango4',
    ];
}
