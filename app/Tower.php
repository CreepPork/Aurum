<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    protected $fillable = ['title', 'position_X', 'position_Y', 'description'];
}
