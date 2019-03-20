<?php

namespace Tests\Feature;

use App\Baker;
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

        $this->assertEquals($season->activity->first()->description, 'season_created');
    }

    /** @test */
    function updating_a_season() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::create();

        $attributes = [
            'note' => 'changed'
        ];

        $expected = [
            'before' => [ 'note' => $season->note ],
            'after'  => $attributes
        ];

        $this->be($season->owner)
            ->patch($season->path(), $attributes);

        tap($season->activity->last(), function ($activity) use ($expected) {
            $this->assertEquals($expected, $activity->changes);
            $this->assertEquals($activity->description, 'season_updated');
        });
    }

    /** @test */
    function adding_a_new_baker() {
        $season = SeasonFactory::create();

        $season->addBaker(['name' => 'Baker Name']);


        $this->assertCount(2, $season->activities);

        tap($season->activities->last(), function ($activity) {
            $this->assertEquals('baker_created', $activity->description);
            $this->assertInstanceOf(Baker::class, $activity->subject);
        });
    }

    /** @test */
    function updating_a_baker() {
        $season = SeasonFactory::withBakers(1)->create();

        $values = [ 'name' => 'Changed Baker Name' ];

        $this->be($season->owner)
            ->patch($season->bakers->first()->path(), $values);

        tap($season->activities->last(), function ($activity) {
            $this->assertEquals('baker_updated', $activity->description);
            $this->assertInstanceOf(Baker::class, $activity->subject);
        });
        $this->assertCount(3, $season->activities);
    }

    /** @test */
    function add_a_new_episode() {
        $season = SeasonFactory::create();

        $values = factory(Episode::class)->raw(['season_id' => $season->id]);

        $season->addEpisode($values);

        tap($season->activities->last(), function ($activity) {
            $this->assertEquals('episode_created', $activity->description);
            $this->assertInstanceOf(Episode::class, $activity->subject);
        });

        $this->assertCount(2, $season->activities);
    }

    /** @test */
    function update_an_episode() {
        $season = SeasonFactory::withEpisodes(1)->create();

        $values = [ 'episode' => 1, 'title' => 'updated title' ];

        $this->be($season->owner)
            ->patch($season->episodes->first()->path(), $values);

        tap($season->activities->last(), function ($activity) {
            $this->assertEquals('episode_updated', $activity->description);
            $this->assertInstanceOf(Episode::class, $activity->subject);
        });

        $this->assertCount(3, $season->activities);
    }

    /** @test */
    function deleting_a_baker() {
        $season = SeasonFactory::withBakers(1)->create();

        $season->bakers[0]->delete();

        $this->assertCount(3, $season->activities);
    }
}
