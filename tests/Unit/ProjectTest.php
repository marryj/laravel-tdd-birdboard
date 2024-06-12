<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_has_a_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals('/projects/'.$project->id, $project->path());
    }

    public function test_it_can_add_a_task()
    {
        $project = Project::factory()->create();
        $task = $project->addTask('Test task');
        $this->assertTrue($project->tasks->contains($task));
    }

    public function test_it_can_invite_a_user()
    {
        $project = Project::factory()->create();
        $project->invite($user = User::factory()->create());
        $this->assertTrue($project->members->contains($user));
    }
}
