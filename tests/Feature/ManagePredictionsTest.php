<?php

namespace Tests\Feature;

use App\Episode;
use App\Result;
use App\User;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePredictionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_of_a_season_can_cast_a_prediction() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withResults(2)
            ->create();

        $episode = factory(Episode::class, 2)->create([
           'season_id' => $season->id
        ])->get(1);

        $values = [
            'result_id' => Result::all()->first()->id,
            'baker_id'  => $season->bakers->first()->id,
            'notes'     => 'This is the notes.'
        ];

        $this->be($season->allMembers->first())
            ->post($episode->path().'/prediction', $values);

        $this->assertDatabaseHas('predictions', $values);
    }

    /** @test */
    public function not_a_member_of_a_season_can_not_cast_predictions() {
        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(1)
            ->withResults(2)
            ->create();

        $this->be(factory(User::class)->create())
            ->post($season->episodes->first()->path().'/prediction')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_not_complete_predictions_when_they_have_not_made_any() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(1)
            ->create();

        $this->be($this->signIn())
            ->post($season->episodes->first()->path().'/complete')
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_complete_their_predictions() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withAddtionalMembers(1)
            ->withResults(2)
            ->withPredictions(2)
            ->create();

        $episode = $season->episodes->get(1);

        $this->be($user = $season->allMembers->first())
            ->post($episode->path().'/complete');

        $this->assertDatabaseHas('completed_predictions', [
            'owner_id' => $user->id,
            'episode_id' => $episode->id
        ]);
    }

    /** @test */
    public function a_member_of_a_season_can_not_add_predictions_to_a_completed_episode() {
        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withAddtionalMembers(1)
            ->withResults(2)
            ->withPredictions(2)
            ->withCompletedPredictions()
            ->create();

        $values = [
            'result_id' => Result::all()->first()->id,
            'baker_id'  => $season->bakers->first()->id,
            'notes'     => 'This is the notes.'
        ];

        $this->be($season->allMembers->first())
            ->post($season->episodes->get(1)->path().'/prediction', $values)
            ->assertStatus(403);
    }

    /** @test */
    public function a_member_can_delete_their_uncompleted_predictions() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withAddtionalMembers(1)
            ->withResults(2)
            ->withPredictions(2)
            ->create();

        $prediction = $season->episodes->get(1)
                ->userPredictions($season->allMembers->first())
                ->first();

        $this->be($prediction->owner)->delete('/prediction/'.$prediction->id.'/delete')
            ->assertRedirect($season->path());

        $this->assertDatabaseMissing('predictions', [
            'id' => $prediction->id,
        ]);
    }

    /** @test */
    public function a_member_cannot_delete_their_completed_predictions() {
        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withResults(2)
            ->withPredictions(2)
            ->create();

        $episode = tap($season->episodes->get(1))->completePredictions(
            $season->allMembers->first()
        );

        $prediction = $episode->userPredictions(
            $season->allMembers->first()
        )->first();

        $this->be($prediction->owner)
            ->delete('/prediction/'.$prediction->id.'/delete')
            ->assertStatus(403);
    }

    /** @test */
    public function unauthorized_users_can_not_delete_predictions() {
        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(2)
            ->withAddtionalMembers(1)
            ->withResults(2)
            ->withPredictions(2)
            ->create();

        $episode = tap($season->episodes->get(1))->completePredictions(
            $season->allMembers->first()
        );

        $prediction = $episode->userPredictions(
            $season->allMembers->first()
        )->first();

        // Unauthorized User
        $this->delete('/prediction/'.$prediction->id.'/delete')
            ->assertRedirect('login');

        // Authorized user
        $this->be($season->allMembers->get(1))->delete('/prediction/'.$prediction->id.'/delete')
            ->assertStatus(403);

        $this->assertDatabaseHas('predictions', ['id' => $prediction->id ]);
    }
}
