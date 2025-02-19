<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaMateriales extends Model
{
    use HasFactory;

    
    protected $table = 'entrega_materiales'; // Nombre de la tabla
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
