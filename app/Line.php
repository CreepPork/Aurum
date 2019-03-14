<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $fillable = ['title'];

    public function stops()
    {
        return $this->hasMany(TrainStop::class)->orderBy('title');
    }
}
