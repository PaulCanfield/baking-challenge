<?php

namespace Tests\Unit;

use App\Baker;
use App\Season;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinalResult extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_season() {
        $finalResult = factory(\App\FinalResult::class)->create();
        $this->assertInstanceOf(Season::class, $finalResult->season);
    }

    /** @test */
    public function it_has_a_baker() {
        $finalResult = factory(\App\FinalResult::class)->create();
        $this->assertInstanceOf(Baker::class, $finalResult->baker);
    }

    /** @test */
    public function it_has_an_owner() {
        $finalResult = factory(\App\FinalResult::class)->create();
        $this->assertInstanceOf(User::class, $finalResult->owner);
    }

    /** @test */
    public function it_can_be_a_winner() {
        $finalResult = factory(\App\FinalResult::class)->state('winner')->create();
        $this->assertTrue($finalResult->winner ? true : false);
    }
}
