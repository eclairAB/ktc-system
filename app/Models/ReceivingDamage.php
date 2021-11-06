<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingDamage extends Model
{
    use HasFactory;
    protected $fillable = [
        'damage_id',
        'component_id',
        'repair_id',
        'receiving_id',
        'location',
        'length',
        'width',
        'quantity',
        'description',
    ];

    public function receiving()
    {
        return $this->HasOne(ContainerReceiving::class, 'id','receiving_id');
    }

    public function damage()
    {
        return $this->HasOne(ContainerDamage::class, 'id','damage_id');
    }

    public function component()
    {
        return $this->HasOne(ContainerComponent::class, 'id','component_id');
    }

    public function repair()
    {
        return $this->HasOne(ContainerRepair::class, 'id','repair_id');
    }

}
