<?php

namespace Tests\Feature;

use App\Episode;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function creating_a_season() {
        $season = SeasonFactory::create();

        $this->assertCount(1, $season->activity);

        $this->assertEquals($season->activity->first()->description, 'season.created');
    }

    /** @test */
    function updating_a_season() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::create();

        $attributes = [
            'note' => 'changed'
        ];

        $this->be($season->owner)->patch($season->path(), $attributes);
        $this->assertTrue($season->activity->contains('description', 'season.updated'));
    }

    /** @test */
    function adding_a_baker() {
        $season = SeasonFactory::create();

        $season->addBaker(['name' => 'Baker Name']);

        $this->assertCount(2, $season->activity);
    }

    /** @test */
    function updating_a_baker() {
        $season = SeasonFactory::withBakers(1)->create();

        $values = [ 'name' => 'Changed Baker Name' ];

        $this->be($season->owner)
            ->patch($season->bakers->first()->path(), $values);

        $this->assertCount(3, $season->activity);
    }

    /** @test */
    function adding_an_episode() {
        $season = SeasonFactory::create();

        $values = factory(Episode::class)->raw(['season_id' => $season->id]);

        $season->addEpisode($values);

        $this->assertCount(2, $season->activity);
    }

    /** @test */
    function updating_a_episode() {
        $season = SeasonFactory::withEpisodes(1)->create();

        $values = [ 'episode' => 1, 'title' => 'updated title' ];

        $this->be($season->owner)
            ->patch($season->episodes->first()->path(), $values);

        $this->assertCount(3, $season->activity);
    }

    /** @test */
    function deleting_a_baker() {
        $season = SeasonFactory::withBakers(1)->create();

        $season->bakers[0]->delete();

        $this->assertCount(3, $season->activity);
    }
}
