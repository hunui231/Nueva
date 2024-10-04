<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cnc extends Model
{
    use HasFactory;

    protected $table = 'tcnc1'; // Nombre de la tabla
    protected $fillable = ['dato', 'meta']; // Campos que se pueden rellenar

}
