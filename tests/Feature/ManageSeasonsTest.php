<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Season;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\SeasonFactory;

class ManageSeasonsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_a_season() {
        $this->signIn();

        $this->get('/season/create')->assertStatus(200);

        $attributes = Season::factory()->raw([ 'owner_id' => auth()->id() ]);

        $response = $this->post('/season', $attributes);
        $season = Season::where($attributes)->first();

        $response->assertRedirect($season->path());

        $this->assertDatabaseHas('seasons',  $attributes);

        $this->get('/season')
            ->assertSee($attributes['year'])
            ->assertSee($attributes['title']);
    }

    /** @test */
    public function authenticated_user_can_update_their_season() {
        $season = SeasonFactory::create();

        $attributes = [
            'note'  => 'Changed',
            'title' => 'Changed Title',
            'year'  => 1910
        ];

        $this->actingAs($season->owner)
            ->patch($season->path(), $attributes)
            ->assertRedirect($season->path());

        $this->get($season->path())->assertOk();

        $this->assertDatabaseHas('seasons', $attributes);
    }

    /** @test */
    public function an_owner_can_update_season_note() {
        $season = SeasonFactory::create();

        $attributes = [ 'note' => 'Changed' ];
        $this->be($season->owner)
            ->patch($season->path(), $attributes);

        $this->assertDatabaseHas('seasons', $attributes);

        $attributes = [ 'note' => null ];
        $this->be($season->owner)
            ->patch($season->path(), $attributes);

        $this->assertDatabaseHas('seasons', $attributes);
    }

    /** @test */
    public function a_season_requires_a_title() {
        $this->actingAs(User::factory()->create());
        $attributes = Season::factory()->raw(['title' => '']);

        $this->post('/season', $attributes)
            ->assertSessionHasErrors('title');
    }


    /** @test */
    public function a_season_requires_a_year() {
        $this->actingAs(User::factory()->create());
        $attributes = Season::factory()->raw(['year' => '']);

        $this->post('/season', $attributes)
            ->assertSessionHasErrors('year');
    }

    /** @test */
    public function a_user_can_view_their_season() {
        $this->be(User::factory()->create());
        $season = Season::factory()->create(['owner_id' => auth()->id()]);

        $this->get($season->path())
            ->assertSee($season->title)
            ->assertSee($season->year)
            ->assertSee($season->note);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_other_users_seasons() {
        $this->be(User::factory()->create());
        $season = Season::factory()->create();
        $this->get($season->path())->assertStatus(403);
    }


    /** @test */
    public function an_authenticated_user_cannot_update_other_users_seasons() {
        $this->signIn();
        $season = Season::factory()->create();
        $this->patch($season->path(), [ ])->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_manage_seasons() {
        $season = Season::factory()->create();

        $this->get('/season')->assertRedirect('login');
        $this->get($season->path())->assertRedirect('login');
        $this->get('/season/create')->assertRedirect('login');
        $this->post('/season', $season->toArray())->assertRedirect('login');
        $this->get($season->path())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_see_all_the_seasons_on_their_dashboard() {
        $maurice = $this->signIn();
        $season = tap(SeasonFactory::create())->invite($maurice);
        $this->get($season->path())->assertSee($season->title);
    }

}
