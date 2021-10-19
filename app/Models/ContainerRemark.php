<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerRemark extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'container_id',
        'remarks'
    ];
}
