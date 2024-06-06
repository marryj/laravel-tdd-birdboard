@extends('layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-center">
            <p class="text-gray-500 text-sm font-normal">
                <a href="/projects">My Projects</a> / {{ $project->title }}
            </p>
            <a class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded"
               href="/projects/create">New Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-6">
                    <h2 class="text-lg text-gray-500 font-normal mb-3">
                        Tasks
                    </h2>
                    @foreach($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{$task->path()}}" method="post">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input name="body"
                                           value="{{$task->body}}"
                                           class="w-full {{$task->completed ? 'text-gray-400' : ''}}">
                                    <input type="checkbox"
                                           name="completed"
                                           onchange="this.form.submit()"
                                            {{$task->completed ? 'checked' : ''}}>
                                </div>

                            </form>
                        </div>
                    @endforeach

                    <div class="card mb-3">
                        <form method="POST" action="{{$project->path() . '/tasks'}}">
                            @csrf
                            <input class="w-full" name="body" placeholder="Begin adding tasks.."/>
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg text-gray-500 font-normal mb-3">General Notes</h2>
                    <form method="POST" action="{{$project->path()}}">
                        @csrf
                        @method('PATCH')
                        <textarea name="notes"
                                  class="card w-full"
                                  style="min-height: 200px"
                                  placeholder="Anything special that you want to make a note of?"
                        >{{$project->notes}}</textarea>
                        <button type="submit" class="button">Save</button>
                    </form>
                </div>
            </div>

            <div class="lg:w-1/4 px-3">
                @include('projects.card')

            </div>
        </div>
    </main>

@endsection