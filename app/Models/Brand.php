<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;


class Brand extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'brands';

    protected $fillable = [
        'name',
        'logo',
        'description',
        'products',
    ];

    protected $casts = [
        'products' => 'array',
    ];
}
