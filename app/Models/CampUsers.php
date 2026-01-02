<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'camp_id',
        'user_id',
    ];

    //belongs to camp
    public function camps()
    {
        return $this->belongsTo(Camps::class, 'camp_id');
    }

    //belongs to users
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
