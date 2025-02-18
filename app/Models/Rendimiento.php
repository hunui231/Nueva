<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendimiento extends Model
{
    use HasFactory;

    
    protected $table = 'rendimiento_operacional_ci'; 
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
