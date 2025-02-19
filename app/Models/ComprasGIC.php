<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprasGIC extends Model
{
    use HasFactory;
    protected $table = 'cumplimiento_compras'; // Nombre de la tabla
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
