<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CncGrafico2 extends Model
{
    use HasFactory;
    
    protected $table = 'tcnc2'; // Nombre de la tabla
    protected $fillable = ['dato', 'meta']; // Campos que se pueden rellenar
}
