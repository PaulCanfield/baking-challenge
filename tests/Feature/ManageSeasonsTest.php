<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Season;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageSeasonsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */

    public function authenticated_user_can_create_a_season() {
        $this->withoutExceptionHandling();

        $this->signIn();

        $this->get('/season/create')->assertStatus(200);

        $attributes = factory('App\Season')->raw([ 'owner_id' => auth()->id() ]);

        $response = $this->post('/seasons', $attributes);
        $season = Season::where($attributes)->first();

        $response->assertRedirect($season->path());

        $this->assertDatabaseHas('seasons',  $attributes);

        $this->get('/seasons')
            ->assertSee($attributes['season'])
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function authenticated_user_can_update_their_season() {
        $this->withoutExceptionHandling();

        $this->signIn();

        $season = factory('App\Season')->create([ 'owner_id' => auth()->id() ]);

        $attributes = [ 'note' => 'Changed' ];

        $this->patch($season->path(), $attributes)
            ->assertRedirect($season->path());

        $this->assertDatabaseHas('seasons', $attributes);
    }

    /** @test */

    public function a_season_requires_a_title() {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Season')->raw(['title' => '']);

        $this->post('/seasons', $attributes)
            ->assertSessionHasErrors('title');
    }


    /** @test */

    public function a_season_requires_a_season() {
        $this->actingAs(factory('App\User')->create());

        $attributes = factory('App\Season')->raw(['season' => '']);

        $this->post('/seasons', $attributes)
            ->assertSessionHasErrors('season');
    }

    /** @test */

    public function a_user_can_view_their_season() {
        $this->be(factory('App\User')->create());

        $this->withoutExceptionHandling();

        $season = factory('App\Season')->create(['owner_id' => auth()->id() ]);

        $this->get($season->path())
            ->assertSee($season->title)
            ->assertSee($season->season)
            ->assertSee($season->note);
    }

    /** @test */

    public function an_authenticated_user_cannot_view_other_users_seasons() {
        $this->be(factory('App\User')->create());

        $season = factory('App\Season')->create();

        $this->get($season->path())->assertStatus(403);
    }


    /** @test */

    public function an_authenticated_user_cannot_update_other_users_seasons() {
        $this->signIn();

        $season = factory('App\Season')->create();

        $this->patch($season->path(), [ ])->assertStatus(403);
    }

    /** @test */

    public function guests_cannot_manage_seasons() {
        $season = factory('App\Season')->create();

        $this->get('/seasons')->assertRedirect('login');

        $this->get('/season/create')->assertRedirect('login');

        $this->post('/seasons', $season->toArray())->assertRedirect('login');

        $this->get($season->path())->assertRedirect('login');
    }
}
