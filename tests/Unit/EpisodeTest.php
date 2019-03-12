<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Season;
use App\Episode;
use Illuminate\Foundation\Testing\WithFaker;
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

}
