<?php

namespace Tests\Feature;

use App\Result;
use Facades\Tests\Setup\SeasonFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagePredictionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_of_a_season_can_cast_a_prediction() {
        $this->withoutExceptionHandling();

        $season = SeasonFactory::withBakers(2)
            ->withEpisodes(1)
            ->withMembers(1)
            ->withEpisodes(1)
            ->create();

        $results = factory(Result::class, 2)->create();

        $values = [
            'result_id' => $results->first()->id,
            'baker_id'  => $season->bakers->first()->id,
            'notes'     => 'This is the notes.'
        ];

        $this->be($season->members()->first())
            ->post('/episode/'.$season->episodes->first()->id.'/prediction', $values);

        $this->assertDatabaseHas('predictions', $values);
    }
}
