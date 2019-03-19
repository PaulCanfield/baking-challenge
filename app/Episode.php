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
}
