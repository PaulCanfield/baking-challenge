<?php

namespace App;

class Baker extends SeasonObject
{
    use RecordActivity;

    public function path() {
        return '/baker/'. $this->id;
    }

    public function isEliminated($episodeNum = null) {
        $query = $this->episodeResults()
            ->join('results', 'results.id', '=', 'episode_results.result_id')
            ->join('episodes', 'episodes.id', 'episode_results.episode_id')
            ->where('results.eliminated', '=', 1);

        if ($episodeNum) {
            $query->where('episodes.episode', '<', $episodeNum);
        }

        return $query->count() > 0 ? true : false;
    }

    public function episodeResults() {
        return $this->hasMany(EpisodeResults::class);
    }
}
