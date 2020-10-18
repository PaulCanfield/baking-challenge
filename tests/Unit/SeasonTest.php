<?php

namespace Tests\Unit;

use App\Models\Baker;
use App\Models\Season;
use App\Models\User;
use App\Models\FinalResult;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeasonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function it_has_a_path() {
        $season = Season::factory()->create();

        $this->assertEquals('/season/'.$season->id, $season->path());
    }

    /** @test */

    public function it_belongs_to_an_owner() {
        $season = Season::factory()->create();
        $this->assertInstanceOf(User::class, $season->owner);
    }

    /** @test */

    public function it_can_add_bakers() {
        $season = Season::factory()->create();

        $baker = $season->addBaker(Baker::factory()->raw());

        $this->assertCount(1, $season->bakers);
        $this->assertTrue($season->bakers->contains($baker));
    }

    /** @test */
    public function it_can_invite_users() {
        $season = SeasonFactory::create();

        $season->invite($user = User::factory()->create());

        $this->assertTrue($season->members->contains($user));
    }

    /** @test */
    public function get_members_includes_owner() {
        $season = SeasonFactory::create();
        $this->assertTrue($season->getMembers()->contains($season->owner));
    }

    /** @test */
    public function a_season_returns_final_predictions_by_user() {
        $season = FinalResult::factory()->create()->season;
        $this->assertEquals(1, $season->finalPredictionsCount($season->allMembers->first()));
    }

    /** @test */
    public function it_will_return_users_predicted_winner() {
        $season = FinalResult::factory()->create()->season;
        $user = $season->allMembers->first();

        FinalResult::factory()->winner()->create([
            'season_id' => $season->id,
            'baker_id' => Baker::factory()->create([ 'season_id' => $season->id ])->id,
            'owner_id' => $user->id
         ]);

        $finalResult = $season->predictedWinner($user);

        $this->assertTrue($finalResult->winner ? true : false);
    }

    /** @test */
    public function it_can_finalize_results() {
        $season = Season::factory()->create();
        $user = $season->allMembers->first();

        FinalResult::factory()->count( 2)->create([
            'season_id' => $season->id,
            'owner_id' => $user->id
        ]);

        FinalResult::factory()->winner()->create([
            'season_id' => $season->id,
            'owner_id' => $user->id
        ]);

        $season->finalizeFinalResults($user);

        $this->assertTrue($season->finalizeFinalResults($user) ? true : false);
    }
}
