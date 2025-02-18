<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CumplimientoTaller extends Model
{
    use HasFactory;
    protected $table = 'produccion_taller'; // Nombre de la tabla
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
