<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /** @test */

    public function has_seasons() {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->seasons);
    }
}
