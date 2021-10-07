<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerSizeType extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'size',
        'type',
    ];
}
