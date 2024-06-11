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

    public function test_it_can_be_completed()
    {

        $task = Task::factory()->create();

        self::assertFalse($task->completed);

        $task->complete();

        self::assertTrue($task->fresh()->completed);

    }

    public function test_it_can_be_incomplete()
    {

        $task = Task::factory()->create(['completed' => true]);

        self::assertTrue($task->completed);

        $task->incomplete();

        self::assertFalse($task->fresh()->completed);

    }
}
