<?php

namespace Tests\Unit;

use App\EpisodeResults;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use App\Season;
use App\Episode;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EpisodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_season() {
        $episode = factory(Episode::class)->create();

        $this->assertInstanceOf(Season::class, $episode->season);
    }

    /** @test */
    public function it_has_a_path() {
        $episode = factory(Episode::class)->create();

        $this->assertEquals('/episode/' . $episode->id, $episode->path());
    }

    /** @test */
    function it_has_a_collection_of_bakers() {
        $season = SeasonFactory::withEpisodes(1)
            ->withBakers(3)
            ->create();

        $episode = $season->episodes->first();

        $this->assertCount(3, $episode->bakers());
    }

    /** @test */
    function it_can_have_results() {
        $season = SeasonFactory::withEpisodes(1)
            ->withBakers(3)
            ->withEpisodeResults(2)
            ->create();

        $this->assertCount(2, $season->episodes->first()->results);
    }

    /** @test */
    function it_can_add_results() {
        $season = SeasonFactory::withEpisodes(1)
            ->withBakers(3)
            ->create();

        $episode = $season->episodes->first();

        $episode->addResult(factory(EpisodeResults::class)->raw([
            'baker_id' => $episode->bakers()->first()->id
        ]));

        $episode->addResult(factory(EpisodeResults::class)->raw([
            'baker_id' => $episode->bakers()->first()->id
        ]));

        $this->assertCount(2, $season->episodes->first()->results);
    }
}
