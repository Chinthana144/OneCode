<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'camp_id',
        'user_id',
        'package_id',
        'purchase_date',
        'invoiceable_id',
        'invoiceable_type',
        'paymethod_id',
        'login_datetime',
        'expire_datetime',
        'mac_address',
        'ip_address',
        'price',
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

    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id');
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    public function paymethod()
    {
        return $this->belongsTo(Paymethods::class, 'paymethod_id');
    }

}//class
