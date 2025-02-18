<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CumplimientoCI extends Model
{
    use HasFactory;

    protected $table = 'cumplimiento_compras_ic';
    
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}