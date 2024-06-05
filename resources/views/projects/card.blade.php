<div class="card" style="height: 200px">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-4 border-l-4 border-sky-300 pl-4">
        <a href="{{$project->path()}}">{{ $project->title }}</a>
    </h3>

    <div class="text-gray-500">{{ Str::limit($project->description, 100) }}</div>
</div>
