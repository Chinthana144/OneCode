<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camps extends Model
{
    use HasFactory;

    protected $fillable  = [
        'name',
        'location',
        'contactPerson',
        'contactPhone',
        'contactEmail',
        'mikritikIP',
        'mikritikPort',
        'mikrotikUsername',
        'mikrotikPassword',
        'radiusSecret',
        'radiusIP',
        'monthly_target',
        'status',
    ];

    public function campusers()
    {
        return $this->hasMany(campusers::class, 'camp_id');
    }

    public function customers()
    {
        return $this->hasMany(Customers::class, 'camp_id');
    }

    public function packages()
    {
        return $this->hasMany(Packages::class, 'camp_id');
    }

    public function subscription()
    {
        return $this->hasMany(Subscriptions::class, 'camp_id');
    }

    public function clientSubscriptions()
    {
        return $this->hasMany(ClientSubscriptions::class, 'camp_id');
    }
}
