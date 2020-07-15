<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'biography',
        'photo',
    ];
}
