<?php

namespace Tests\Unit;

use App\Models\User;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_has_a_user() {
        $season = SeasonFactory::create();
        $this->assertInstanceOf(User::class, $season->activity->first()->user);
    }

    /** @test */
    function it_has_a_subject() {
        $season = SeasonFactory::create();
        $this->assertIsObject($season->activity->first()->subject);
    }
}
