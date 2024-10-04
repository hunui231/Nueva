<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tlogistica2 extends Model
{
    use HasFactory;

    protected $table = 'tlogistica2';
    protected $fillable = ['dato', 'meta'];
}
