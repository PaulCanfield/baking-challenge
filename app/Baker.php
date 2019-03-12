<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Baker extends Model
{
    protected $guarded = [ ];

    protected $touches = [ 'season' ];

    public function season() {
        return $this->belongsTo(Season::class);
    }

    public function path() {
        return '/baker/'. $this->id;
    }
}
