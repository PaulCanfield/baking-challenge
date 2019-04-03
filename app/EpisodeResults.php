<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpisodeResults extends Model
{
    protected $guarded = [ ];

    public function result() {
        return $this->belongsTo(Result::class);
    }

    public function baker() {
        return $this->belongsTo(Baker::class);
    }
}
