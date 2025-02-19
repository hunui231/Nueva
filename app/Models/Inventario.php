<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'informacion_inventarios'; // Nombre de la tabla
    protected $fillable = [
        'mes',
        'desempeno', // Cambiado de 'tendencia' a 'desempeno'
        'area_cumplimiento', // Cambiado de 'area' a 'area_cumplimiento'
    ];
}
