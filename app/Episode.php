<?php

namespace App;

use Illuminate\Support\Collection;
use mysql_xdevapi\Exception;

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

    public function activeBakers() {
        $bakers = new Collection();

        foreach ($this->season->bakers as $baker) {
            if (!$baker->isEliminated($this->episode)) {
                $bakers[] = $baker;
            }
        }

        return $bakers;
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

    public function completePredictions($ownerId = null) {
        $ownerId = $ownerId ? $ownerId : auth()->user()->id;

        return $this->completedPredictions()->create([
            'owner_id' => $ownerId
        ]);
    }

    public function isCompleted($ids = null) {
        $query = $this->completedPredictions();

        if ($ids !== null) {
            $query->whereIn('owner_id', (is_array($ids) ? $ids : [ $ids ]));
        }

        $count = $query->count();

        if ($ids) {
            if (is_array($ids)) {
                return $count == count($ids);
            } else {
                return $count == 1;
            }
        }
        return $count == count($this->season->members);
    }

    public function canPredict($ownerId = null) {
        if (!$ownerId) {
            $ownerId = auth()->user()->id;
        }

        $completed = CompletedPredictions::select('episodes.id as id')
            ->join('episodes', 'episodes.id', '=', 'completed_predictions.episode_id')
            ->where('completed_predictions.owner_id', '=', $ownerId)
            ->where('episodes.episode', '<=', $this->episode)
            ->where('episodes.season_id', '=', $this->season->id)
            ->count();

        if ($completed - $this->episode == -1) {
            return true;
        }
        return false;
    }

    public function finalize() {
        if (!$this->results) {
            throw new Exception('Unable to finalize results of an episode when the episode has no results.');
        }
        $this->finalized = true;
        $this->save();
    }

    public function unfinalize() {
        if (!$this->finalized) {
            throw new Exception('Unable to unfinalize results of an episode when unfinalized.');
        }
        $this->finalized = false;
        $this->save();
    }
}
