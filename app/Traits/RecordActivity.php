<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use App\Models\Activity;

trait RecordActivity
{
    protected static function dispatchedEvents() {
        return [
            'created', 'updated', 'deleted'
        ];
    }

    public static function bootRecordActivity() {
        foreach (self::dispatchedEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $event = strtolower(class_basename($model)).'_'.$event;
                $model->recordActivity($event);
            });
        }
    }

    public function activity() {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function recordActivity($description) {


        $values = [
            'user_id'     => $this->getUserId(),
            'description' => $description,
            'changes'     => $this->getActivityChanges(),
            'season_id'   => $this->getSeasonId()
        ];

        $this->activity()->create($values);
    }

    public function getActivityChanges() {
        return $this->wasChanged() && $this->getChanges() ? [
            'before' => Arr::except(array_diff($this->getOriginal(), $this->getAttributes()), 'updated_at'),
            'after'  => Arr::except($this->getChanges(), 'updated_at')
        ] : null;
    }

    public function getUserId() {
        return $this->season->owner->id;
    }

    public function getSeasonId() {
        return $this->season_id;
    }
}
