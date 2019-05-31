<?php

namespace Tests\Unit;

use App\Baker;
use App\Season;
use App\User;
use Facades\Tests\Setup\SeasonFactory;
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

    /** @test */
    public function it_can_invite_users() {
        $season = SeasonFactory::create();

        $season->invite($user = factory(User::class)->create());

        $this->assertTrue($season->members->contains($user));
    }

    /** @test */
    public function get_members_includes_owner() {
        $season = SeasonFactory::create();
        $this->assertTrue($season->getMembers()->contains($season->owner));
    }

    /** @test */
    public function a_season_returns_final_predictions_by_user() {
        $season = factory(\App\FinalResult::class)->create()->season;
        $this->assertEquals(1, $season->finalPredictionsCount($season->allMembers->first()));
    }

    /** @test */
    public function it_will_return_users_predicted_winner() {
        $season = factory(\App\FinalResult::class)->create()->season;
        $user = $season->allMembers->first();

        factory(\App\FinalResult::class)->state('winner')->create([
            'season_id' => $season->id,
            'baker_id' => factory(Baker::class)->create([ 'season_id' => $season->id ])->id,
            'owner_id' => $user->id
         ]);

        $finalResult = $season->predictedWinner($user);

        $this->assertTrue($finalResult->winner ? true : false);
    }

    /** @test */
    public function it_can_finalize_results() {
        $season = factory(Season::class)->create();
        $user = $season->allMembers->first();

        factory(\App\FinalResult::class, 2)->create([
            'season_id' => $season->id,
            'owner_id' => $user->id
        ]);

        factory(\App\FinalResult::class)->state('winner')->create([
            'season_id' => $season->id,
            'owner_id' => $user->id
        ]);

        $season->finalizeFinalResults($user);

        $this->assertTrue($season->finalizeFinalResults($user) ? true : false);
    }
}
