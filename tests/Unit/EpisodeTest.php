<?php

namespace Tests\Unit;

use App\Models\Baker;
use App\Models\EpisodeResult;
use App\Models\Result;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use App\Models\Season;
use App\Models\Episode;
//use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EpisodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_season() {
        $episode = Episode::factory()->create();
        $this->assertInstanceOf(Season::class, $episode->season);
    }

    /** @test */
    public function it_has_a_path() {
        $episode = Episode::factory()->create();
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

        $episode->addResult(EpisodeResult::factory()->raw([
            'baker_id' => $episode->bakers()->first()->id
        ]));

        $episode->addResult(EpisodeResult::factory()->raw([
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
            'result_id' => Result::factory()->create(['eliminated' => 1])->id
        ]);

        $this->assertCount(2, $secondEpisode->activeBakers());
    }

    /** @test */
    public function only_user_predictions_appear_in_user_predictions() {
        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withAddtionalMembers(1)
            ->withResults(2)
            ->withPredictions(2)
            ->create();

        $episode = $season->episodes->get(1);
        $owner = $episode->predictions->first()->owner;

        $this->assertCount(2, $episode->userPredictions($owner));
    }

    /** @test */
    public function it_can_be_completed() {
        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withAddtionalMembers(1)
            ->withResults(2)
            ->withPredictions(2)
            ->withCompletedPredictions(2)
            ->create();

        $user = $season->allMembers->first();

        $this->assertTrue($season->episodes->get(1)->isCompleted($user));
    }

    /** @test */
    public function it_can_only_be_completed_if_predictions_exist() {
        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(1)
            ->withResults(2)
            ->create();

        $season->episodes->first()->completePredictions(
            $season->allMembers->first()
        );

        $this->assertFalse($season->episodes->first()->isCompleted());
    }

    /** @test */
    public function a_user_can_only_predict_the_next_episode_after_a_completed_episode() {
        $season = SeasonFactory::withBakers(4)
            ->withResults(2)
            ->create();

        $episodes = Episode::factory()->count(3)->create([
            'season_id' => $season->id,
        ]);

        $firstEpisode = $episodes->get(1);
        $secondEpisode = $episodes->get(2);

        $user = $this->signIn($season->allMembers->first());

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

        $this->assertEquals(1, Episode::factory()->create([
            'season_id' => $season
        ])->episode);

        $this->assertEquals(2, Episode::factory()->create([
            'season_id' => $season
        ])->episode);

        $this->assertEquals(3, Episode::factory()->create([
            'season_id' => $season
        ])->episode);
    }

    /** @test */
    public function it_will_return_the_number_of_points_player_earned() {
        $this->withoutExceptionHandling();

        $firstEpisode   = Episode::factory()->create();
        $episode        = Episode::factory()->hasResults()->finalized()
            ->create([
                'season_id' => $firstEpisode->season->id
            ]);

        $user      = $episode->season->allMembers->first();

        $starBakerResult     = Result::where('result', '=', 'Star Baker')->first();
        $eliminatedResult    = Result::where('result', '=', 'Eliminated')->first();

        $bakerJordi  = $episode->season->bakers->get(0);
        $bakerRiker  = Baker::factory()->create([ 'season_id' => $episode->season->id ]);

        $this->assertEquals(0, $firstEpisode->userPoints($user));

        // Correct Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerJordi->id,
            'result_id'  => $starBakerResult->id
        ]);

        // Incorrect Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerRiker->id,
            'result_id'  => $eliminatedResult->id
        ]);

        $this->assertEquals(0, $episode->userPoints($user));

        $episode->completePredictions($user);

        $this->assertEquals(10, $episode->userPoints($user));
    }

    /** @test */
    public function it_will_add_a_bonus_if_all_predictions_are_correct() {
        $this->withoutExceptionHandling();

        $firstEpisode = Episode::factory()->create();
        $episode      = Episode::factory()->hasResults()->finalized()
            ->create([ 'season_id' => $firstEpisode->season->id ]);

        $user         = $episode->season->allMembers->first();

        $starBakerResult       = Result::where('result', '=', 'Star Baker')->first();
        $eliminatedResult      = Result::where('result', '=', 'Eliminated')->first();

        $bakerJordi  = $episode->season->bakers->get(0);
        $bakerPicard = $episode->season->bakers->get(1);

        // Correct Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerJordi->id,
            'result_id'  => $starBakerResult->id
        ]);

        // Incorrect Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerPicard->id,
            'result_id'  => $eliminatedResult->id
        ]);
        $episode->completePredictions($user);

        $this->assertEquals($episode->userPoints($user), 30);
    }

    /** @test */
    public function un_finalized_episodes_will_not_return_points() {
        $this->withoutExceptionHandling();

        $episode   = Episode::factory()->hasResults()->create();
        $user      = $episode->season->allMembers->first();

        $starBakerResult       = Result::where('result', '=', 'Star Baker')->first();
        $eliminatedResult      = Result::where('result', '=', 'Eliminated')->first();

        $bakerJordi  = $episode->season->bakers->get(0);
        $bakerPicard = $episode->season->bakers->get(1);

        // Correct Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerJordi->id,
            'result_id'  => $starBakerResult->id
        ]);

        // Correct Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerPicard->id,
            'result_id'  => $eliminatedResult->id
        ]);
        $episode->completePredictions($user);

        $this->assertFalse($episode->userPoints($user));
    }

    /** @test */
    public function correct_predictions_are_calculated_correctly() {
        $this->withoutExceptionHandling();

        $firstEpisode = Episode::factory()->create();
        $episode      = Episode::factory()->hasResults()->finalized()->create([
            'season_id' => $firstEpisode->season->id
        ]);
        $user      = $episode->season->allMembers->first();

        $starBakerResult       = Result::where('result', '=', 'Star Baker')->first();
        $eliminatedResult      = Result::where('result', '=', 'Eliminated')->first();

        $bakerJordi  = $episode->season->bakers->get(0);
        $bakerPicard = $episode->season->bakers->get(1);

        // Correct Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerJordi->id,
            'result_id'  => $starBakerResult->id
        ]);

        // Correct Prediction
        $episode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerPicard->id,
            'result_id'  => $eliminatedResult->id
        ]);
        $episode->completePredictions($user);

        $this->assertEquals(2, $episode->correctPredictionsCount($user));

        $secondEpisode   = Episode::factory()->hasResults()->finalized()->create([
            'season_id' => $episode->season->id
        ]);

        $episode->season->load('bakers');
        $bakerRiker = $episode->season->bakers->get(2);

        $this->assertEquals( 0, $secondEpisode->correctPredictionsCount($user));

        // Correct Prediction
        $secondEpisode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerRiker->id,
            'result_id'  => $starBakerResult->id
        ]);

        // Incorrect Prediction
        $secondEpisode->addPrediction([
            'owner_id'   => $user->id,
            'baker_id'   => $bakerJordi->id,
            'result_id'  => $eliminatedResult->id
        ]);
        $secondEpisode->completePredictions($user);

        $this->assertEquals( 1, $secondEpisode->correctPredictionsCount($user));
    }
}
