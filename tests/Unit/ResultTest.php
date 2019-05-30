<?php

namespace Tests\Unit;

use App\Result;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_generate_a_key() {
        $result = factory(Result::class)->create([ 'result' => 'Star Baker']);
        $this->assertEquals('star_baker', $result->key);
    }
}
