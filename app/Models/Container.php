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
}
