<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessPlanes extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'user_id',
        'package_id',
        'paymethod_id',
        'accessable_type',
        'accessable_id',
        'purchaseDate',
        'purchaseDateTime',
        'login_at',
        'expire_at',
        'mac_address',
        'ip_address',
        'price',
        'status',
    ];

    public function accessable()
    {
        return $this->morphTo();
    }

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

    public function paymethod()
    {
        return $this->belongsTo(Camps::class, 'paymethod_id');
    }

}//class
