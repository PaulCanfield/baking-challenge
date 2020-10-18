<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\SeasonFactory;
use App\Episode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Season;

class SeasonEpisodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_episodes_to_season() {
        $this->post(Season::factory()->create()->path() .'/episode')
            ->assertRedirect('login');
    }

    /** @test */
    public function only_owner_of_a_season_can_add_an_episode() {
        $season = SeasonFactory::create();

        $episode = Episode::factory()->raw();

        $this->actingAs($this->signIn())
            ->post($season->path() .'/episode', $episode)
            ->assertStatus(403);

        $this->assertDatabaseMissing('episodes', $episode);
    }

    /** @test */
    public function only_owner_of_a_season_can_update_a_episode() {
        $season = SeasonFactory::withEpisodes(1)
            ->create();

        $update = [
            'title' => 'changed value',
            'episode' => '12'
        ];

        $this->actingAs($this->signIn())
            ->patch($season->episodes->first()->path(), $update)
            ->assertStatus(403);

        $this->assertDatabaseMissing('episodes', $update);
    }

    /** @test */
    public function a_season_can_have_episodes() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withEpisodes(1, ['episode' => 1])
            ->create();

        $episode = Episode::factory()->make([
            'season_id' => $season->id
        ]);

        $this->be($season->owner)
            ->post($season->path().'/episode', $episode->toArray());

        $this->assertDatabaseHas('episodes', $episode->toArray());

        $this->be($season->owner)->get($season->path())
            ->assertSee($episode['title']);
    }

    /** @test */
    public function an_episode_can_be_updated() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withEpisodes(1)
            ->create();

        $values = [
            'title' => 'Changed Value'
        ];

        $this->actingAs($season->owner)
            ->patch($season->episodes->first()->path(), $values);

        $this->assertDatabaseHas('episodes', $values);
    }


    /** @test */
    public function an_episode_title_can_be_updated() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withEpisodes(1)
            ->create();

        $values = [
            'title' => 'Changed Value'
        ];

        $this->actingAs($season->owner)
            ->patch($season->episodes->first()->path(), $values);

        $this->assertDatabaseHas('episodes', $values);
    }

    /** @test */
    public function an_episode_requires_a_title() {
        $season = SeasonFactory::create();

        $episode = Episode::factory()->raw([
            'season_id' => $season->id,
            'title' => ''
        ]);

        $this->actingAs($season->owner)
            ->post($season->path() .'/episode', $episode)
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function the_owner_of_a_season_can_finalize_an_episode_with_results() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(4)
            ->withEpisodes(1)
            ->withResults(2)
            ->withEpisodeResults(2)
            ->create();

        $episode = $season->episodes->first();

        $this->be($episode->season->owner)
            ->post($episode->path().'/finalize');

        $this->assertTrue((bool) $episode->refresh()->finalized);

        $episode->unfinalize();

        $this->assertFalse((bool) $episode->finalized);
    }
}
