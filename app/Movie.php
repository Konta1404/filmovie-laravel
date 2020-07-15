<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'name',
        'year',
        'rating',
        'poster',
        'slider_image',
        'description',
        'categories',
        'actors',
        'directors'
    ];
}
