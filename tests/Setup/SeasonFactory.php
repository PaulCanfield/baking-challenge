<?php

namespace Tests\Setup;

use App\Episode;
use App\EpisodeResults;
use App\Result;
use App\Season;
use App\Baker;
use App\User;

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

    public function withCompletedEpisodes($episodes = null, $members = null, $options = [ ]) {
        $this->completedPredictions = [
            'episodes'   => $episodes ?: $this->episodes['count'],
            'members'    => $members ?: $this->members['count'],
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

    public function withMembers($count = 0, $options = [ ]) {
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

    public function withResults($count = 0, $options = [ ]) {
        $this->results = factory(Result::class, $count)->create($options);
        return $this;
    }

    public function withPredictions($count = 0, $episodes = null, $members = null, $options = [ ]) {
        $this->predictions = [
            'count' => $count ?: 0,
            'episodes' => $episodes ?: $this->episodes['count'],
            'members' => $members ?: $this->members['count'],
            'options' => $options
        ];

        return $this;
    }

    public function create( ) {
        $season = factory(Season::class)->create([
            'owner_id' => $this->user ?? factory(User::class)
        ]);

        $seasonId = [ 'season_id' => $season->id ];

        factory(Baker::class, $this->bakerCount)->create($seasonId);

        factory(Episode::class, $this->episodes['count'])
            ->create(array_merge($seasonId, $this->episodes['options']));

        factory(User::class, $this->members['count'])
            ->create()
            ->each(function ($user) use ($season) {
                $season->members()->attach($user);
            });

        foreach ($season->episodes as $index => $episode) {
            if ($index >= $this->episodeResults['episodes']) {
                break;
            }

            $this->results->each(function () use ($episode) {
                factory(EpisodeResults::class)->create([
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

                foreach ($season->members as $memberIndex => $member) {
                    if ($memberIndex >= $this->predictions['members']) {
                        break;
                    }

                    for ($i = 0 ; $i < $this->predictions['count'] ; $i++) {
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

                    foreach ($season->members as $memberIndex => $member) {
                        if ($memberIndex > $this->completedPredictions['members']) {
                            continue 2;
                        }

                        $episode->completePredictions([ 'owner_id' => $member->id ]);
                    }
                }
            }
        }

        return $season;
    }
}
