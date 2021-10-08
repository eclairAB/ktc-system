<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerHeight extends Model
{
    use HasFactory;
    protected $fillable = [
        'height_code',
        'height_name',
    ];
}
