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
    protected $results    = [ 'count' => 0, 'nEpisodes' => 0, 'options' => [ ] ];
    protected $members    = [ 'count' => 0, 'options' => [ ] ];
    protected $user;

    public function withBakers($count) {
        $this->bakerCount = $count;
        return $this;
    }

    public function withEpisodes($count, $options = [ ]) {
        $this->episodes = [
            'count' => $count,
            'options' => $options
        ];

        return $this;
    }

    public function withEpisodeResults($count, $nEpisodes = 0, $options = [ ]) {
        $this->results = [
            'count'     => $count,
            'nEpisodes' => $nEpisodes ?: $this->episodes['count'],
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

        $results = factory(Result::class, $this->results['count'])
            ->create($this->results['options']);

        foreach ($season->episodes as $index => $episode) {
            if ($index >= $this->results['nEpisodes']) {
                break;
            }

            $results->each(function () use ($episode) {
                factory(EpisodeResults::class)->create([
                    'episode_id' => $episode->id,
                    'baker_id' => $episode->season->bakers->random()
                ]);
            });
        }

        return $season;
    }
}
