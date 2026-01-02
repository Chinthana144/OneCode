<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSubscriptions extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'user_id',
        'customer_id',
        'package_id',
        'paymethod_id',
        'purchaseDate',
        'purchaseDateTime',
        'subscriptionStartTime',
        'subscriptionEndTime',
        'price',
        'macAddress',
        'status',
    ];

    public function camp()
    {
        return $this->belongsTo(Camps::class, 'camp_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }

    public function paymethod()
    {
        return $this->belongsTo(PayMethods::class, 'paymethod_id');
    }

}
