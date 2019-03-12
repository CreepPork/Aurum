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
        $this->belongsTo(RoadSide::class);
    }

    public function region()
    {
        $this->belongsTo(Region::class);
    }

    public function parish()
    {
        $this->belongsTo(Parish::class);
    }

    public function village()
    {
        $this->belongsTo(Village::class);
    }
}
