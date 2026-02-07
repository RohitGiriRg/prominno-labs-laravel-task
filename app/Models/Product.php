<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',  // ✅ NOT seller_id
        'name',
        'description',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
}