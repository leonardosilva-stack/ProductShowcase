<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Banner extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'banners';
    protected $fillable = [
        'title',
        'description',
        'desktopImage',
        'tabletImage',
        'mobileImage',
        'link',
        'status',
        'expirationDate'
    ];

    protected $dates = ['createdAt', 'updatedAt', 'expirationDate'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
