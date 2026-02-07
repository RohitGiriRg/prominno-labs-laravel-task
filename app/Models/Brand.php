<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Brand extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'detail',
        'price',
        'image',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
