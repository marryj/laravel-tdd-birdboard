<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', ['body' => 'Test task']);

        $this->get($project->path())->assertSee('Test task');
    }

    public function test_a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        $attributes = Task::factory()->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }

    public function test_only_the_owner_of_the_project_may_add_tasks()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->post($project->path().'/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    public function test_a_task_can_be_updated()
    {
        $project = ProjectFactory::withTasks(1)
            ->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true,
        ]);
    }

    public function test_only_the_owner_of_the_project_may_update_tasks()
    {
        $this->signIn();
        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed'
        ])
            ->assertStatus(403);// Forbidden

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }
}
