<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Configuration extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'configs';

    protected $fillable = [
        'siteTitle',
        'siteDescription',
        'socialLinks',
        'aboutText',
        'image',
        'logo',
        'createdAt',
        'updatedAt',
    ];

    protected $casts = [
        'socialLinks' => 'array',
        'createdAt'   => 'datetime',
        'updatedAt'   => 'datetime',
    ];
}
