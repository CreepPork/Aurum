<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainStop extends Model
{
    protected $fillable = ['title', 'position_X', 'position_Y', 'line_id'];

    /**
     * A train stop has only one line assigned to it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }
}
