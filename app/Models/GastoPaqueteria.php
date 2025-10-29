<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoPaqueteria extends Model
{
    use HasFactory;

    protected $table = 'gasto_paqueteria';
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}