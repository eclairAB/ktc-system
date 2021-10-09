<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerReceiving extends Model
{
    use HasFactory;
    protected $fillable = [
        'inspected_by',
        'inspected_date',
        'container_no',
        'client_id',
        'size_type',
        'class',
        'type',
        'height',
        'empty_loaded',
        'manufactured_date',
        'yard_loacation',
        'acceptance_no',
        'consignee',
        'hauler',
        'plate_no',
        'upload_photo',
        'signature',
    ];
}
