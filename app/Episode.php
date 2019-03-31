<?php

namespace App;

class Episode extends SeasonObject
{
    use RecordActivity;

    public function path() {
        return "/episode/{$this->id}";
    }

    public function episodeName() {
        return "Episode {$this->episode}";
    }

    public function bakers() {
        return $this->season->bakers;
    }

    public function results() {
        return $this->hasMany(EpisodeResults::class);
    }

    public function addResult($values) {
        return $this->results()->create($values);
    }
}
