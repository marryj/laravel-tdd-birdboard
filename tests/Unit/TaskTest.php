<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{

    use RefreshDatabase;

    public function test_it_belongs_to_project()
    {
        $task = Task::factory()->create();
        self::assertInstanceOf(Project::class, $task->project);
    }

    /**
     * A basic unit test example.
     */
    public function test_it_has_a_path(): void
    {
        $task = Task::factory()->create();
        $this->assertEquals('/projects/'.$task->project->id.'/tasks/'.$task->id, $task->path());
    }
}
