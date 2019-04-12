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

    public function predictions() {
        return $this->hasMany(Prediction::class);
    }

    public function userPredictions($userId = null) {
        return $this->predictions()
            ->where('owner_id', '=', $userId ?: auth()->user()->id )
            ->get();
    }

    public function completedPredictions() {
        return $this->hasMany(CompletedPredictions::class);
    }

    public function addResult($values) {
        return $this->results()->create($values);
    }

    public function addPrediction($values) {
        return $this->predictions()->create(array_merge([
            'owner_id' => $values['owner_id'] ?? auth()->user()->id
        ], $values));
    }

    public function completePredictions($values = [ ]) {
        return $this->completedPredictions()->create([
            'owner_id' => $values['owner_id'] ?? auth()->user()->id
        ]);
    }

    public function isCompleted($ids = null) {
        $query = $this->completedPredictions();

        if ($ids !== null) {
            $query->whereIn('owner_id', (is_array($ids) ? $ids : [ $ids ]));
        }

        return $query->count() == ($ids ? (is_array($ids) ? count($ids) : 1) : count($this->season->members));
    }
}
