<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_can_have_tasks()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $this->post($project->path().'/tasks', ['body' => 'Test task']);
        $this->get($project->path())->assertSee('Test task');
    }

    public function test_a_task_requires_a_body()
    {
        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $attributes = Task::factory()->raw(['body' => '']);

        $this->post($project->path().'/tasks', $attributes)->assertSessionHasErrors('body');
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
        $this->withoutExceptionHandling();
        $this->signIn();

        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $task = $project->addTask('test task');

        $this->patch($task->path(), [
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
        $project = Project::factory()->create();
        $task = $project->addTask('test task');
        $this->patch($task->path(), [
            'body' => 'changed'
        ])
            ->assertStatus(403);// Forbidden

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }
}
