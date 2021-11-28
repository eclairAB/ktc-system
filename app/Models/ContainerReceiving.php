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
        // 'type',
        // 'height'
        'empty_loaded',
        'manufactured_date',
        'yard_location',
        // 'acceptance_no',
        'consignee',
        'hauler',
        'plate_no',
        'signature',
        'remarks',
        'type_id',
    ];

    public function photos()
    {
        return $this->hasMany(ContainerPhoto::class, 'container_id')->where('container_type','receiving');
    }

    public function client()
    {
        return $this->HasOne(Client::class, 'id','client_id');
    }

    public function sizeType()
    {
        return $this->HasOne(ContainerSizeType::class, 'id','size_type');
    }

    public function containerClass()
    {
        return $this->HasOne(ContainerClass::class, 'id','class');
    }

    public function yardLocation()
    {
        return $this->belongsTo(YardLocation::class, 'yard_location');
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function container()
    {
        return $this->HasOne(Container::class, 'container_no', 'container_no');
    }

    public function type()
    {
        return $this->HasOne(Type::class, 'id', 'type_id');
    }
}
