<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'customerType_id',
        'name',
        'duration',
        'price',
        'bandwidth',
        'downloadlimit',
        'uploadlimit',
        'status',
    ];

    public function camp()
    {
        return $this->belongsTo(Camps::class, 'camp_id');
    }

    //belongs to customer type
    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'customerType_id');
    }

    public function subscription()
    {
        return $this->hasMany(Subscriptions::class, 'package_id');
    }

    public function clientSubscription()
    {
        return $this->hasMany(ClientSubscriptions::class, 'package_id');
    }
}
