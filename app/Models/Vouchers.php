<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'expire_date',
    ];

    public function accessable()
    {
        return $this->morphOne(AccessPlanes::class, 'accessable');
    }

}//class
