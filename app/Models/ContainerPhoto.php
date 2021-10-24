<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerPhoto extends Model
{
    use HasFactory;
    protected $fillable = [
        'container_id',
        'container_type',
        'storage_path',
    ];
}
