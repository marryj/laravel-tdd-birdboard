<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;


    public function test_creating_a_project(): void
    {
        $project = ProjectFactory::create();

        self::assertCount(1, $project->activity);

        tap($project->activity->last(), function ($activity) {
            self::assertEquals('created_project', $activity->description);
            self::assertNull($activity->changes);
        });
    }

    public function test_updating_a_project(): void
    {
        $project = ProjectFactory::create();
        $originalTitle = $project->title;

        $project->update([
            'title' => 'Changed'
        ]);

        self::assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use ($originalTitle) {
            self::assertEquals('updated_project', $activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'Changed'],
            ];

            self::assertEquals($expected, $activity->changes);
        });

    }

    public function test_creating_a_new_task(): void
    {
        /** @var Project $project */
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();
        $task = $project->addTask('Some task');

        self::assertCount(1, $task->activity);

        tap($task->activity->last(), function ($activity) {
            self::assertEquals('created_task', $activity->description);
            self::assertInstanceOf(Task::class, $activity->subject);
        });

    }

    public function test_completing_a_task(): void
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'foobar',
                'completed' => true,
            ]);

        self::assertCount(2, $project->tasks[0]->activity);

        tap($project->tasks[0]->activity->last(), function ($activity) {
            self::assertEquals('completed_task', $activity->description);
            self::assertInstanceOf(Task::class, $activity->subject);
            self::assertEquals('foobar', $activity->subject->body);
        });

    }

    public function test_incompleting_task(): void
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'foobar',
                'completed' => true,
            ]);

        self::assertCount(2, $project->tasks[0]->activity);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => false,
        ]);

        $project->refresh();

        self::assertCount(3, $project->tasks[0]->activity);
        self::assertEquals('incompleted_task', $project->tasks[0]->activity->last()->description);
    }

    public function test_deleting_a_task()
    {
        /** @var Project $project */
        $project = ProjectFactory::withTasks(1)->create();
        $project->tasks[0]->delete();

        self::assertCount(2, $project->tasks[0]->activity);
    }
}
