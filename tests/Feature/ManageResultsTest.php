<?php

namespace Tests\Feature;

use App\Episode;
use App\Result;
use App\User;
use Facades\Tests\Setup\SeasonFactory;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class ManageResultsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_owner_of_a_season_can_create_a_result() {
        $season = SeasonFactory::withEpisodes(1)
            ->ownedBy($user = $this->signIn())
            ->create();

        $values = factory(Result::class)->raw();

        Session::put('requestReferrer', $season->path());

        $this->post(
            '/result',
            $values
        )->assertRedirect($season->path());

        $this->assertDatabaseHas('results', $values);
    }

    /** @test */
    public function unauthorized_users_can_not_create_results() {
        $this->signIn();

        $this->post(
            '/result'
        )->assertStatus(403);

        $this->get('/result')
            ->assertStatus(403);
    }
}
