<?php

namespace App;

class Baker extends SeasonObject
{
    use RecordActivity;

    public function path() {
        return '/baker/'. $this->id;
    }

    public function scopeEliminated($query) {
        return $query->join('episode_results', 'bakers.id', '=', 'episode_results.baker_id');
    }
}
