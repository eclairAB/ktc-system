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
        'remarks',
        'eir',
    ];

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    public function photos()
    {
        return $this->hasMany(ContainerPhoto::class, 'container_id')->where('container_type','releasing');
    }

    public function container()
    {
        return $this->HasOne(Container::class, 'releasing_id', 'id');
    }

    public function receiving()
    {
        return $this->HasOne(ContainerReceiving::class, 'container_no', 'container_no');
    }
}
