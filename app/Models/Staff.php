<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_id',
        'id_no',
        'firstname',
        'lastname',
        'user_id',
        'user_type',
        'contact_no',
    ];
}
