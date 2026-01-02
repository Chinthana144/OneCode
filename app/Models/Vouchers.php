<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
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
        'voucherStartTime',
        'voucherEndTime',
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

    public function payMethod()
    {
        return $this->belongsTo(Paymethods::class, 'paymethod_id');
    }
}//class
