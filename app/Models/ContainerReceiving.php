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
        'container_photo_id',
        'signature',
        'remarks'
    ];

    public function photos()
    {
        return $this->hasMany(ContainerPhoto::class, 'id');
    }

    public function client()
    {
        return $this->HasOne(Client::class, 'id','client_id');
    }

    public function sizeType()
    {
        return $this->HasOne(ContainerSizeType::class, 'id','size_type');
    }

    public function class()
    {
        return $this->HasOne(ContainerClass::class, 'id','class');
    }
}
