<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageAccess extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'page_id',
        'camp_id',
        'create',
        'view',
        'edit',
        'delete',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function page()
    {
        return $this->belongsTo(Pages::class);
    }

    public function camp()
    {
        return $this->belongsTo(Camps::class);
    }
}
