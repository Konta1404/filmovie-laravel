<?php

namespace App;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Illuminate\Database\Eloquent\Model;
use Actuallymab\LaravelComment\HasComments;

class Movie extends Model implements Commentable
{
    use HasComments;

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
