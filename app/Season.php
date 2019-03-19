<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use RecordActivity;

    protected $guarded = [ ];

    public function path() {
        return "/season/{$this->id}";
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function bakers() {
        return $this->hasMany(Baker::class);
    }

    public function episodes() {
        return $this->hasMany(Episode::class);
    }

    public function addBaker($values) {
        return $this->bakers()->create($values);
    }

    public function addEpisode($values) {
        return $this->episodes()->create($values);
    }

    public function activity() {
        return $this->hasMany(Activity::class)->latest();
    }
}
