<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Setup\SeasonFactory;
use App\Season;
use App\Baker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeasonBakersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_bakers_to_seasons() {
        $this->post(factory('App\Season')->create()->path() .'/baker')
            ->assertRedirect('login');
    }

    /** @test */
    public function only_owner_of_a_season_can_add_a_baker() {
        $season = app(SeasonFactory::class)
            ->create();

        $baker = factory(Baker::class)->raw();

        $this->actingAs($this->signIn())
            ->post($season->path() .'/baker', $baker)
            ->assertStatus(403);

        $this->assertDatabaseMissing('bakers', $baker);
    }

    /** @test */
    public function only_owner_of_a_season_can_update_a_baker() {
        $season = app(SeasonFactory::class)
            ->withBakers(1)
            ->create();

        $update = [ 'name' => 'changed value' ];

        $this->actingAs($this->signIn())
            ->patch($season->bakers->first()->path(), $update)
            ->assertStatus(403);

        $this->assertDatabaseMissing('bakers', $update);
    }


    /** @test */
    public function a_season_can_have_bakers() {
        $this->signIn();

        $season = auth()->user()->seasons()->create(
            factory(Season::class)->raw()
        );

        $baker = factory(Baker::class)->make();

        $this->post($season->path() . '/baker', $baker->toArray());

        $this->get($season->path())
            ->assertSee($baker->name);

    }

    /** @test */
    public function a_baker_can_be_updated() {
        $season = app(SeasonFactory::class)
            ->withBakers(1)
            ->create();

        $values = [
            'name' => 'Changed Value'
        ];

        $this->actingAs($season->owner)
            ->patch($season->bakers->first()->path(), $values);

        $this->assertDatabaseHas('bakers', $values);
    }

    /** @test */
    public function a_baker_requires_a_name() {
        $this->signIn();

        $season = auth()->user()->seasons()->create(
            factory(Season::class)->raw()
        );

        $baker = factory(Baker::class)->raw( [ 'name' => ''] );

        $this->post( $season->path() . '/baker', $baker)
            ->assertSessionHasErrors('name');
    }
}
