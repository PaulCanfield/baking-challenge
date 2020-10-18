<?php

namespace Tests\Setup;

use App\Models\Episode;
use App\Models\EpisodeResult;
use App\Models\Result;
use App\Models\Season;
use App\Models\Baker;
use App\Models\User;

class SeasonFactory
{
    protected $bakerCount = 0;
    protected $episodes   = [ 'count' => 0, 'options' => [ ] ];
    protected $episodeResults    = [ 'count' => 0, 'episodes' => 0, 'options' => [ ] ];
    protected $members    = [ 'count' => 0, 'options' => [ ] ];
    protected $predictions = [ 'count' => 0, 'episodes' => 0, 'members' => 0, 'options' => [ ]];
    protected $completedPredictions = [ 'episodes' => 0, 'members' => 0, 'options' => [ ] ];
    protected $user;
    protected $results;

    public function withBakers($count) {
        $this->bakerCount = $count;
        return $this;
    }

    public function withCompletedPredictions($episodes = null, $members = null, $options = [ ]) {
        $this->completedPredictions = [
            'episodes'   => $episodes ?: $this->episodes['count'],
            'members'    => $members  ?: $this->members['count'],
            'options'    => $options
        ];
        return $this;
    }

    public function withEpisodes($count, $options = [ ]) {
        $this->episodes = [
            'count' => $count,
            'options' => $options
        ];

        return $this;
    }

    public function withEpisodeResults($count, $episodes = 0, $options = [ ]) {
        $this->episodeResults = [
            'count'     => $count,
            'episodes' => $episodes ?: $this->episodes['count'],
            'options'   => $options
        ];

        return $this;
    }

    public function withAddtionalMembers($count = 0, $options = [ ]) {
        $this->members = [
            'count' => $count,
            'options' => $options
        ];

        return $this;
    }

    public function ownedBy(User $user) {
        $this->user = $user;
        return $this;
    }

    static public function results() {
        return Result::all();
    }

    public function withResults($count = 0, $options = [ ]) {
        $this->results = Result::factory()->count($count)->create($options);
        return $this;
    }

    public function withPredictions($count = 0, $episodes = null, $members = null, $options = [ ]) {
        $this->predictions = [
            'count'    => $count    ?: 0,
            'episodes' => $episodes ?: $this->episodes['count'],
            'members'  => $members  ?: ( $this->members['count'] ? $this->members['count'] : 1 ),
            'options'  => $options
        ];

        return $this;
    }

    public function create( ) {
        $season = Season::factory()->create([
            'owner_id' => $this->user ?? User::factory()->create()
        ]);

        $seasonId = [ 'season_id' => $season->id ];

        Baker::factory()->count($this->bakerCount)->create($seasonId);
        Episode::factory()->count($this->episodes['count'])->create([
            'season_id' => $season->id
        ]);

        User::factory()->count($this->members['count'])
            ->create()
            ->each(function ($user) use ($season) {
                $season->members()->attach($user);
            });

        foreach ($season->episodes as $index => $episode) {
            if ($index >= $this->episodeResults['episodes']) {
                break;
            }

            $this->results->each(function () use ($episode) {
                EpisodeResult::factory()->create([
                        'episode_id' => $episode->id,
                        'baker_id' => $episode->season->bakers->random()->id
                ]);
            });
        }

        if ($this->predictions['count']) {
            foreach ($season->episodes as $index => $episode) {
                if ($index >= $this->predictions['episodes']) {
                    break;
                }

                foreach ($season->allMembers as $memberIndex => $member) {
                    if ($memberIndex >= $this->predictions['members']) {
                        break;
                    }

                    for ($i = 0 ; $i < $this->predictions['count'] ; $i++) {
                        if ($episode->episode == 1) {
                            continue;
                        }

                        $episode->addPrediction([
                            'owner_id' => $member->id,
                            'baker_id' => $season->bakers->random()->id,
                            'result_id' => $this->results->random()->id
                        ]);
                    }
                }
            }

            if ($this->completedPredictions['episodes'] && $this->completedPredictions['members']) {
                foreach ($season->episodes as $episodeIndex => $episode) {
                    if ($episodeIndex >= $this->completedPredictions['episodes']) {
                        break;
                    }

                    foreach ($season->allMembers as $memberIndex => $member) {
                        if ($memberIndex > $this->completedPredictions['members']) {
                            continue 2;
                        }

                        $episode->completePredictions($member);
                    }
                }
            }
        }

        return $season;
    }
}
