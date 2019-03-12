<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $guarded = [ ];

    protected $touches = [ 'season' ];

    public function path() {
        return "/episode/{$this->id}";
    }

    public function season() {
        return $this->belongsTo(Season::class);
    }

    public function episodeName() {
        return "Episode {$this->episode}";
    }
}
