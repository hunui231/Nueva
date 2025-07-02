<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria5s extends Model
{
    use HasFactory;

    protected $table = 'auditoria_5s';
    protected $fillable = ['mes', 'desempeno', 'area_cumplimiento'];
}
