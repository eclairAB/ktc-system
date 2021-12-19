<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EirNumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'eir_no',
        'container_id',
    ];
}
