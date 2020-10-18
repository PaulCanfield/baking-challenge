<?php

namespace Tests\Unit;

use App\Models\Baker;
use App\Models\Season;
use App\Models\User;
use Tests\TestCase;
use App\Models\FinalResult;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinalResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_season() {
        $finalResult = FinalResult::factory()->create();
        $this->assertInstanceOf(Season::class, $finalResult->season);
    }

    /** @test */
    public function it_has_a_baker() {
        $finalResult = FinalResult::factory()->create();
        $this->assertInstanceOf(Baker::class, $finalResult->baker);
    }

    /** @test */
    public function it_has_an_owner() {
        $finalResult = FinalResult::factory()->create();
        $this->assertInstanceOf(User::class, $finalResult->owner);
    }

    /** @test */
    public function it_can_be_a_winner() {
        $finalResult = FinalResult::factory()->winner()->create();
        $this->assertTrue($finalResult->winner ? true : false);
    }
}
