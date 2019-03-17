<?php

namespace Tests\Feature;

use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creating_a_season_generates_activity() {
        $season = SeasonFactory::create();

        $this->assertCount(1, $season->activity);

        $this->assertEquals($season->activity->first()->description, 'Season Created');
    }

    /** @test */
    function updating_a_season_generates_activity() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::create();

        $attributes = [
            'note' => 'changed'
        ];

        $this->be($season->owner)->patch($season->path(), $attributes);
        $this->assertTrue($season->activity->contains('description', 'Season Updated'));
    }

    /** @test */
    function adding_a_baker_records_season_activity() {
        $season = SeasonFactory::create();

        $season->addBaker(['name' => 'Baker Name']);

        $this->assertCount(2, $season->activity);
    }
}
