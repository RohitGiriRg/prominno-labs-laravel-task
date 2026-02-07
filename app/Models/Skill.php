<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Skill extends Model
{
    protected $fillable = [
        'name',
    ];

    public function sellers()
    {
        return $this->belongsToMany(User::class, 'seller_skill');
    }
}
