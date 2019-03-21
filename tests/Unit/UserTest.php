<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_seasons() {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Collection::class, $user->seasons);
    }

    /** @test */
    public function a_user_has_accessible_seasons() {
        SeasonFactory::ownedBy(
            $maurice = $this->signIn()
        )->create();

        $this->assertCount(1, $maurice->joinedSeasons());

        $season = tap(
            SeasonFactory::ownedBy(
                factory(User::class)->create()
            )->create()
        )->invite(
            factory(User::class)->create()
        );

        $this->assertCount(1, $maurice->joinedSeasons());

        $season->invite($maurice);

        $this->assertCount(2, $maurice->joinedSeasons());
    }
}
