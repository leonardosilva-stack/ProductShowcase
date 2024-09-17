<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Product extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'title',
        'image',
        'nutritionalTable',
        'brand_id',
        'status',
    ];

    protected $dates = ['createdAt', 'updatedAt', 'expirationDate'];

    protected $casts = [
        'nutritionalTable' => 'array',
        'status' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
