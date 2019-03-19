<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeasonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function it_has_a_path() {
        $season = factory('App\Season')->create();

        $this->assertEquals('/season/'.$season->id, $season->path());
    }

    /** @test */

    public function it_belongs_to_an_owner() {
        $season = factory('App\Season')->create();

        $this->assertInstanceOf('App\User', $season->owner);
    }

    /** @test */

    public function it_can_add_bakers() {
        $season = factory('App\Season')->create();

        $baker = $season->addBaker(factory('App\Baker')->raw());

        $this->assertCount(1, $season->bakers);
        $this->assertTrue($season->bakers->contains($baker));
    }
}
