<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymethods extends Model
{
    use HasFactory;

    protected $fillable = [
        'paymethod_name',
    ];

    public function accessPlan()
    {
        return $this->hasMany(AccessPlanes::class, 'paymethod_id');
    }
}
