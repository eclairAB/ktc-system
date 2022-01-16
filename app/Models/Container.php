<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;
    protected $fillable = [
        'container_no',
        'client_id',
        'size_type',
        'class',
        'receiving_id',
        'releasing_id',
        'type_id',
    ];

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

    public function receiving()
    {
        return $this->belongsTo(ContainerReceiving::class);
    }

    public function releasing()
    {
        return $this->belongsTo(ContainerReleasing::class);
    }

    public function eirNoIn()
    {
        return $this->HasOne(EirNumber::class, 'container_id','id')->where('eir_no','ilike','%I-%');
    }

    public function eirNoOut()
    {
        return $this->HasOne(EirNumber::class, 'container_id','id')->where('eir_no','ilike','%O-%');
    }

    public function type()
    {
        return $this->HasOne(Type::class, 'id','type_id');
    }
}
