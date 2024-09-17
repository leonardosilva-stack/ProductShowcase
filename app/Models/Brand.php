<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Brand extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'brands';

    protected $fillable = [
        'name',
        'products',
    ];

    protected $casts = [
        'products' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', '_id');
    }
}
