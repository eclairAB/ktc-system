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
        'signature',
        'remarks'
    ];

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function photos()
    {
        return $this->hasMany(ContainerPhoto::class, 'container_id');
    }

    public function container()
    {
        return $this->HasOne(Containers::class, 'container_no', 'container_no');
    }

    public function receiving()
    {
        return $this->HasOne(ContainerReceiving::class, 'container_no', 'container_no');
    }
}
