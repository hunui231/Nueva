<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapP extends Model
{
    use HasFactory;

    protected $table = 'producción_scrap_ci'; 
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
