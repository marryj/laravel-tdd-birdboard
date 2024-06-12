<?php

namespace Tests\Unit;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ActivityTest extends TestCase
{

    use RefreshDatabase;


    /**
     * A basic unit test example.
     */
    public function test_it_has_a_user(): void
    {
        $user = $this->signIn();

        $attributes = Project::factory()->raw(['owner_id' => $user]);

        $project = Project::factory()->create($attributes);

        self::assertEquals($user->id, $project->activity->first()->user->id);
    }
}
