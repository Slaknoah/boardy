<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advert extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'images_links' => 'array'
    ];

    public static array $defaultFields = [
        'id',
        'title',
        'images_links->0 as image',
        'price',
        'created_at'
    ];

}
