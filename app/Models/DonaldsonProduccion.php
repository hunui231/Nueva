<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonaldsonProduccion extends Model
{
    use HasFactory;

    protected $table = 'produccion_donaldson'; 
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
