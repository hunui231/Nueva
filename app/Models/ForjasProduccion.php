<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForjasProduccion extends Model
{
    use HasFactory;

    protected $table = 'produccion_forjas'; 
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
