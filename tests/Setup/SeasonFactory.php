<?php

namespace Tests\Setup;

use App\Episode;
use App\Season;
use App\Baker;
use App\User;

class SeasonFactory
{
    protected $bakerCount = 0;
    protected $episodes = [ 'count' => 0, 'options' => [ ] ];
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

    public function ownedBy(User $user) {
        $this->user = $user;

        return $this;
    }

    public function create( ) {
        $season = factory(Season::class)->create([
            'owner_id' => $this->user ?? factory(User::class)
        ]);

        $seasonId = [ 'season_id' => $season->id ];

        factory(Baker::class, $this->bakerCount)->create([
            'season_id' => $season->id
        ]);

        factory(Episode::class, $this->episodes['count'])
            ->create(array_merge($seasonId, $this->episodes['options']));

        return $season;
    }
}
