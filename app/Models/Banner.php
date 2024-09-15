<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Banner extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'banners';
    protected $fillable = [
        'title', 'description', 'desktopImage', 'tabletImage', 'mobileImage', 'link', 'status', 'expirationDate'
    ];

    protected $dates = ['createdAt', 'updatedAt', 'expirationDate'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
