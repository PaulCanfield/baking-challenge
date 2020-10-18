<?php

namespace App;

use Exception;
use Illuminate\Support\Collection;

class Episode extends SeasonObject
{
    use RecordActivity;

    private $pointsBase = 10;
    private $pointsBonus = 10;

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
        return $this->hasMany(EpisodeResult::class);
    }

    public function predictions() {
        return $this->hasMany(Prediction::class);
    }

    public function userPredictions($user = null) {
        $user = $user ?: auth()->user();

        return $this->predictions()
            ->where('owner_id', '=', $user->id)
            ->get();
    }

    public function completedPredictions() {
        return $this->hasMany(CompletedPredictions::class);
    }

    public function addResult($values) {
        return $this->results()->create($values);
    }

    public function addResults(array $values) {
        foreach ($values as $index => $value) {
            $this->addResult($value);
        }
    }

    public function addPrediction($values)
    {
        if ($this->episode == 1) {
            return false;
        }

        return $this->predictions()->create(array_merge([
            'owner_id' => $values['owner_id'] ?? auth()->user()->id
        ], $values));
    }

    public function completePredictions($user = null) {
        $user = $user ?: auth()->user();

        if ($this->episode == 1) {
            return false;
        }

        if ($this->userPredictions($user)->count() == 0) {
            return false;
        }

        return $this->completedPredictions()->create([
            'owner_id' => $user->id
        ]);
    }

    public function isCompleted($ids = null) {
        $query = $this->completedPredictions();

        if ($ids !== null) {
            if ($ids instanceof Illuminate\Support\Collection) {
                $include = $ids->pluck('id')->toArray();
            } else {
                if ($ids instanceof User) {
                    $include = [ $ids->id ];
                } else {
                    $include = $ids;
                }
            }

            $query->whereIn('owner_id', $include);
        }

        $count = $query->count();

        if ($ids == null) {
            return $count == count($this->season->getMembers());
        }

        return $count == 1;
    }

    public function canPredict($owner = null) {
        $owner = $owner ?: auth()->user();

        if ($this->episode == 1) {
            return false;
        }

        $completed = Episode::select('episodes.id as id')
            ->leftJoin('completed_predictions', 'episodes.id', '=', 'completed_predictions.episode_id')
            ->where([
                [ 'completed_predictions.owner_id', '=', $owner->id ],
                [ 'episodes.season_id', '=', $this->season->id ],
                [ 'episodes.episode', '<=', $this->episode ]
            ])
            ->orWhere([
                [ 'episodes.episode', '=', 1 ],
                [ 'episodes.season_id', '=', $this->season->id ]
            ])
            ->count();

        if ($completed - $this->episode == -1) {
            return true;
        }
        return false;
    }

    public function correctPredictionsCount($owner = null) {
        $owner = $owner ?: auth()->user();

        if ($this->episode == 1) {
            return false;
        }

        if (!$this->finalized) {
            return false;
        }

        $where = [
            [ 'episodes.id', '=', $this->id ],
            [ 'predictions.owner_id', '=', $owner->id ]
        ];

        return CompletedPredictions::select('*')
            ->join('episodes', 'episodes.id', '=', 'completed_predictions.episode_id')
            ->join('predictions', 'episodes.id', '=', 'predictions.episode_id')
//            ->join('results', 'results.id', '=', 'predictions.result_id')
            ->join('episode_results', function($join) {
                $join->on(
                    'episode_results.baker_id', '=', 'predictions.baker_id'
                )->on(
                    'episode_results.episode_id', '=', 'episodes.id'
                )->on(
                    'episode_results.result_id', '=', 'predictions.result_id'
                );
            })
            ->where($where)
            ->count();
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

    public function bonus($user = null, $correctPredictions = null) {
        $user = $user ?: auth()->user();
        $correctPredictions = $correctPredictions ?: $this->correctPredictionsCount($user);
        $count = $this->userPredictions($user)->count();

        if ($count > 0 && $count == $correctPredictions) {
            return $this->pointsBonus;
        } else {
            return 0;
        }
    }

    public function userPoints($user = null) {
        if (!$this->finalized) {
            return false;
        }

        $user = $user ?: auth()->user();

        $correctPredictions = $this->correctPredictionsCount($user);

        return ($this->pointsBase * $correctPredictions) + $this->bonus($user, $correctPredictions);
    }
}
