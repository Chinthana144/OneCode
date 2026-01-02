<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;

    protected $fillable  = [
        'customerType',
    ];

    //has many customers
    public function customers()
    {
        return $this->hasMany(CustomerType::class, 'customerType_id');
    }

    //has many packages
    public function packages()
    {
        return $this->hasMany(CustomerType::class, 'customerType_id');
    }
}
