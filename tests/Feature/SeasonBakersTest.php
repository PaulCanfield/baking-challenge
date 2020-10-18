<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Facades\Tests\Setup\SeasonFactory;
use App\Season;
use App\Baker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeasonBakersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_bakers_to_seasons() {
        $this->post(Season::factory()->create()->path() .'/baker')
            ->assertRedirect('login');
    }

    /** @test */
    public function only_owner_of_a_season_can_add_a_baker() {
        $season = SeasonFactory::create();

        $baker = Baker::factory()->raw();

        $this->be(User::factory()->create())
            ->post($season->path() .'/baker', $baker)
            ->assertStatus(403);

        $this->assertDatabaseMissing('bakers', $baker);
    }

    /** @test */
    public function only_owner_of_a_season_can_update_a_baker() {
        $season = SeasonFactory::withBakers(1)->create();

        $update = [ 'name' => 'changed value' ];

        $this->actingAs($this->signIn())
            ->patch($season->bakers->first()->path(), $update)
            ->assertStatus(403);

        $this->assertDatabaseMissing('bakers', $update);
    }


    /** @test */
    public function a_season_can_have_bakers() {
        $this->withoutExceptionHandling();
        $this->signIn();

        $season = auth()->user()->seasons()->create(
            Season::factory()->raw()
        );

        $baker = Baker::factory()->make(['name' => "Chet O'Connle", 'season_id' => null]);

        $this->post($season->path() . '/baker', $baker->toArray());

        $this->get($season->path())
            ->assertSee($baker->name);
    }

    /** @test */
    public function a_baker_can_be_updated() {
        $season = SeasonFactory::withBakers(1)->create();

        $values = [
            'name' => 'Changed Value'
        ];

        $this->actingAs($season->owner)
            ->patch($season->bakers->first()->path(), $values);

        $this->assertDatabaseHas('bakers', $values);
    }

    /** @test */
    public function a_baker_requires_a_name() {
        $season = SeasonFactory::create();

        $baker = Baker::factory()->raw( [ 'name' => '' ] );

        $this->be($season->owner)
            ->post( $season->path() . '/baker', $baker)
            ->assertSessionHasErrors('name', null, 'baker');
    }
}
