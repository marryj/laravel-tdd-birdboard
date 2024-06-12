<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordActivity
{

    public array $oldAttributes = [];

    public static function bootRecordActivity(): void
    {
        foreach (self::recordableEvents() as $event) {
            self::$event(function (self $model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });

            if ($event === 'updated') {
                static::updating(function (self $model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    public function recordActivity(string $description): void
    {
        $this->activity()->create([
            'user_id' => ($this->project ?? $this)->owner->id,
            'description' => $description,
            'changes' => $this->activityChanges($description),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
        ]);
    }

    protected function activityChanges($description): ?array
    {
        if ($this->wasChanged()) {
            $before = array_diff($this->oldAttributes, $this->getAttributes());
            unset($before['created_at'], $before['updated_at']);

            return [
                'before' => $before,
                'after' => $this->getChanges(),
            ];
        }

        return null;
    }

    public function activity(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public static function recordableEvents(): array
    {
        if (isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }

        return ['created', 'updated'];
    }

    function activityDescription(string $description): string
    {
        return "{$description}_".strtolower(class_basename($this));//created_task
    }
}