<div class="card mt-6">
    <ul class="text-xs  list-decimal">
        @foreach ($project->activity as $activity)
            <li class="{{ $loop->last ? '' : 'pb-2' }}">
                @include ("projects.activity.{$activity->description}")
                <span class="text-gray-400">
                    {{ $activity->created_at->diffForHumans(null, true) }}
                </span>
            </li>
        @endforeach
    </ul>
</div>