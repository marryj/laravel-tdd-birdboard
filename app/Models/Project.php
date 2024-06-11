<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory,
        RecordActivity;

    public $guarded = [];

    public function path()
    {
        return "/projects/$this->id";
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(
            [
                'body' => $body,
                'project_id' => $this->id
            ]
        );
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
