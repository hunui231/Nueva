<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientesNuevosP extends Model
{
    use HasFactory;

    protected $table = 'clientes_nuevos_mes'; // Nombre de la tabla
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}
