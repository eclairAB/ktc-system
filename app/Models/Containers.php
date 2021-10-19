<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Containers extends Model
{
    use HasFactory;
    protected $fillable = [
        'container_no',
        'client_id',
        'size_type',
        'class',
        'date_received',
        'date_released',
    ];
}
