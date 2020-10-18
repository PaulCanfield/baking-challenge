<?php

namespace Tests\Unit;

use App\Models\Result;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_will_generate_a_key() {
        $result = Result::factory()->create([ 'result' => 'Star Baker']);
        $this->assertEquals('star_baker', $result->key);
    }
}
