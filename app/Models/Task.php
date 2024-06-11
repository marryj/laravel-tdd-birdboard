<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory,
        RecordActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    public static array $recordableEvents = ['created', 'deleted'];

    protected function casts(): array
    {
        return [
            'completed' => 'boolean'
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    public function path(): string
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('incompleted_task');
    }

}
