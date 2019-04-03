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
        return Baker::select('*', 'bakers.id as id')
            ->where('bakers.season_id', '=', $this->season->id)
            ->leftJoin('episode_results', 'bakers.id', '=', 'episode_results.baker_id')
            ->leftJoin('results', 'episode_results.result_id', '=', 'results.id')
            ->where('results.eliminated', '==', 0)
            ->orWhereNull('episode_results.id')
            ->get();
    }

    public function results() {
        return $this->hasMany(EpisodeResults::class);
    }

    public function addResult($values) {
        return $this->results()->create($values);
    }
}
