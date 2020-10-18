<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Season;
use App\Baker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BakerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_season() {
        $baker = Baker::factory()->create();
        $this->assertInstanceOf(Season::class, $baker->season);
    }

    /** @test */
    public function it_has_a_path() {
        $baker = Baker::factory()->create();
        $this->assertEquals('/baker/' . $baker->id, $baker->path());
    }
}
