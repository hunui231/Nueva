<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatisfaccionCliente extends Model
{

    use HasFactory;

    protected $table = 'satisfaccion_cliente'; // Nombre de la tabla
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
