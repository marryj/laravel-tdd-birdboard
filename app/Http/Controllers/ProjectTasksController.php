<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        Gate::authorize('update', $project);

        request()->validate([
            'body' => 'required',
        ]);

        $project->addTask(request('body'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        Gate::authorize('update', $project);

        request()->validate([
            'body' => 'required',
        ]);

        $task->update([
            'body' => request('body'),
            'completed' => request()->has('completed')// checkbox: if it`s not checked you won`t see it
        ]);

        return redirect($project->path());
    }
}
