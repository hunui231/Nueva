<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarioJlg extends Model
{
    use HasFactory;

    protected $table = 'inventario_jlg';
    protected $fillable = [
        'mes',
        'desempeno',
        'area_cumplimiento',
    ];
}