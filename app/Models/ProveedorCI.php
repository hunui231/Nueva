<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorCI extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_proveedores_ci';
    
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
