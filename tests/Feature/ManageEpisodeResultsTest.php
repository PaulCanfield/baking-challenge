<?php

namespace Tests\Feature;

use App\Models\EpisodeResult;
use App\Models\Result;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\SeasonFactory;

class ManageEpisodeResultsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_owner_of_a_season_can_update_episode_results() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(4)
            ->withEpisodes(1)
            ->create();

        $result = Result::factory()->create();

        $episode = $season->episodes->first();

        $values = EpisodeResult::factory()->raw([
            'baker_id' => $episode->bakers()->random()->id,
            'result_id' => $result->id,
        ]);

        $this->be($season->owner)->post(
            "/episode/{$episode->id}/result",
            $values
        );

        $this->assertCount(1, $episode->results);
        $this->assertDatabaseHas('episode_results', $values);
    }

    /** @test */
    public function not_owners_of_season_cannont_update_episode_results() {
        $season = SeasonFactory::withBakers(4)
            ->withEpisodes(1)
            ->create();

        $this->actingAs($this->signIn())
            ->post("/episode/{$season->episodes->first()->id}/result")
            ->assertStatus(403);
    }
}
