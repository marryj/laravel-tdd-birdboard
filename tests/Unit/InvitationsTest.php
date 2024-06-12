<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_a_project_can_invite_a_user(): void
    {
        $project = Project::factory()->create();
        $project->invite($newUser = User::factory()->create());

        $this->actingAs($newUser)
            ->post(route('projects.tasks.store', ['project' => $project]), $task = [
                'body' => 'Invited user task',
            ]);

        $this->assertDatabaseHas('tasks', $task);
    }
}
