<?php

namespace Tests\Unit;

use App\EpisodeResults;
use App\Result;
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
    public function it_has_a_collection_of_bakers() {
        $season = SeasonFactory::withEpisodes(1)
            ->withBakers(3)
            ->create();

        $episode = $season->episodes->first();

        $this->assertCount(3, $episode->bakers());
    }

    /** @test */
    public function it_can_have_results() {
        $season = SeasonFactory::withEpisodes(1)
            ->withBakers(3)
            ->withResults(2)
            ->withEpisodeResults(2)
            ->create();

        $this->assertCount(2, $season->episodes->first()->results);
    }

    /** @test */
    public function it_can_add_results() {
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

    /** @test */
    public function eliminated_bakers_dont_appear_in_list_of_bakers() {
        $season = SeasonFactory::withBakers(3)
            ->create();

        $episodes = [[
            'title' => 'Pastry Week'
        ], [
            'title' => 'Bread Week'
        ]];

        $firstEpisode  = $season->addEpisode($episodes[0]);
        $secondEpisode = $season->addEpisode($episodes[1]);

        $firstEpisode->addResult([
            'baker_id'  => $season->bakers->first()->id,
            'result_id' => factory(Result::class)->create(['eliminated' => 1])->id
        ]);

        $this->assertCount(2, $secondEpisode->activeBakers());
    }

    /** @test */
    public function only_user_predictions_appear_in_user_predictions() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(1)
            ->withMembers(2)
            ->withResults(2)
            ->withPredictions(2)
            ->create();

        $episode = $season->episodes->first();
        $owner = $episode->predictions->first()->owner;

        $this->assertCount(2, $episode->userPredictions($owner->id));
    }

    /** @test */
    public function it_can_be_completed() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withMembers(2)
            ->withResults(2)
            ->withPredictions(2)
            ->withCompletedEpisodes()
            ->create();

        $this->assertTrue($season->episodes->first()->isCompleted());
    }

    /** @test */
    public function a_user_can_only_predict_the_next_episode_after_a_completed_episode() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(4)
            ->withMembers(1)
            ->withResults(2)
            ->create();

        $firstEpisode = factory(Episode::class)->create([
            'season_id' => $season->id,
            'episode'   => 1
        ]);

        $secondEpisode = factory(Episode::class)->create([
            'season_id' => $season->id,
            'episode'   => 2
        ]);

        $user = $this->signIn($season->members->first());

        $this->assertTrue($firstEpisode->canPredict());
        $this->assertFalse($secondEpisode->canPredict());

        $firstEpisode->addPrediction([
            'owner_id' => $user->id,
            'result_id' => SeasonFactory::results()->first()->id,
            'baker_id' => $season->bakers->first()->id
        ]);
        $firstEpisode->completePredictions();

        $this->assertFalse($firstEpisode->canPredict());
        $this->assertTrue($secondEpisode->canPredict());
    }

    /** @test */
    public function it_can_be_finalized_and_unfinalized() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(4)
            ->withEpisodes(1)
            ->withMembers(1)
            ->withResults(2)
            ->withEpisodeResults(2)
            ->create();

        $episode = $season->episodes->first();

        $episode->finalize();

        $this->assertTrue($episode->finalized);

        $episode->unfinalize();

        $this->assertFalse($episode->finalized);
    }

    /** @test */
    public function episodes_are_created_in_sequential_order() {
        $season = SeasonFactory::create();

        $this->assertEquals(1, factory(Episode::class)->create([
            'season_id' => $season
        ])->episode);

        $this->assertEquals(2, factory(Episode::class)->create([
            'season_id' => $season
        ])->episode);

        $this->assertEquals(3, factory(Episode::class)->create([
            'season_id' => $season
        ])->episode);
    }
}
