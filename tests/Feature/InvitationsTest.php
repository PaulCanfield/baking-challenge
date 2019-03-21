<?php

namespace Tests\Feature;

use App\User;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_season_can_invite_a_user() {
        $season = SeasonFactory::create();

        $season->invite($newUser = factory(User::class)->create());

        $this->signIn($newUser);

        $this->post(action('SeasonBakersController@store', $season), ['name' => 'Changed']);

        $this->assertDatabaseHas('bakers', ['name' => 'Changed']);
    }
}
