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

    public function subscriptions()
    {
        return $this->hasMany(Subscriptions::class, 'paymethod_id');
    }
}
