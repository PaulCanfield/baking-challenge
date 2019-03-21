<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeasonObject extends Model
{
    protected $guarded = [ ];

    protected $touches = [ 'season' ];

    public function season() {
        return $this->belongsTo(Season::class);
    }

    public function getUserId() {
        return $this->season->owner->id;
    }
}
