<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerReleasing extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_no',
        'conglone',
        'hauler',
        'plate_no',
        'seac_no',
        'upload_photo',
        'signature',
    ];
}
