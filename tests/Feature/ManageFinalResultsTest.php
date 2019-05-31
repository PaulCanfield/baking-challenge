<?php

namespace Tests\Feature;

use App\Baker;
use App\FinalResult;
use App\Season;
use App\User;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageFinalResultsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_add_final_results() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(3)->create();

        $user = $season->allMembers->first();

        $values = [
            'baker_id'  => $season->bakers->first()->id,
            'winner'    => true
        ];

        $this->be($user)->post($season->path().'/finalResult', $values);

        $this->assertDatabaseHas('final_results', $values);
    }

    /** @test */
    public function only_a_member_of_a_season_can_add_final_results() {
        $season = SeasonFactory::withBakers(3)->create();

        $user = factory(User::class)->create();

        $values = factory(FinalResult::class)->raw([
            'baker_id'  => $season->bakers->first()->id,
            'winner'    => true
        ]);

        $this->be($user)->post($season->path().'/finalResult', $values)
            ->assertStatus(403);

        $this->assertDatabaseMissing('final_results', $values);
    }

    /** @test */
    public function unathenticated_user_can_not_add_final_results() {
        $season = SeasonFactory::withBakers(3)->create();
        $this->post($season->path().'/finalResult', [ ])->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_with_three_predictions_can_finalize_results() {
        $season = factory(Season::class)->create();

        $bakers = factory(Baker::class, 3)->create([ 'season_id' => $season->id ]);

        $user = $season->allMembers->first();

        $this->be($user)->post($season->path().'/finalResult', [
            'baker_id' => $bakers->get(0)->id,
            'winner' => true
        ]);

        $this->be($user)->post($season->path().'/finalResult', [
            'baker_id' => $bakers->get(1)->id
        ]);

        $this->be($user)->post($season->path().'/finalResult/finalize')
            ->assertStatus(403);


        $this->be($user)->post($season->path().'/finalResult', [
            'baker_id' => $bakers->get(2)->id
        ]);

        $this->be($user)->post($season->path().'/finalResult/finalize');

        $this->assertDatabaseHas('finalized_final_results', [
            'owner_id' => $user->id,
            'season_id' => $season->id
        ]);

        $this->be($user)->post($season->path().'/finalResult/finalize')
            ->assertStatus(403);
    }
}
