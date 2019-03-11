<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusStop extends Model
{
    protected $fillable = [
        'title', 'stop_code', 'common_code', 'position_X', 'position_Y', 'road_side', 'road', 'street', 'region', 'parish', 'village'
    ];

    public function roadSide()
    {
        $this->hasOne(RoadSide::class);
    }

    public function region()
    {
        $this->hasOne(Region::class);
    }

    public function parish()
    {
        $this->hasOne(Parish::class);
    }

    public function village()
    {
        $this->hasOne(Village::class);
    }
}
