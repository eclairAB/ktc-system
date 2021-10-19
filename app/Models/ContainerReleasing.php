<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerReleasing extends Model
{
    use HasFactory;
    protected $fillable = [
        'inspected_by',
        'inspected_date',
        'container_no',
        'booking_no',
        'consignee',
        'hauler',
        'plate_no',
        'seal_no',
        'upload_photo',
        'signature',
        'remarks'
    ];
}
