<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 2019-03-19
 * Time: 09:39
 */

namespace App;

use Illuminate\Support\Arr;


trait RecordActivity
{
    public static function bootRecordActivity() {
        $recordableEvents = [
            'created', 'updated', 'deleted'
        ];

        foreach ($recordableEvents as $event) {
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
            'user_id'     => class_basename($this) == 'Season' ? $this->owner->id : $this->season->owner->id,
            'description' => $description,
            'changes'     => $this->getActivityChanges(),
            'season_id'   => class_basename($this) == 'Season' ? $this->id : $this->season_id
        ];

        $this->activity()->create($values);
    }

    public function getActivityChanges() {
        return $this->wasChanged() && $this->getChanges() ? [
            'before' => Arr::except(array_diff($this->getOriginal(), $this->getAttributes()), 'updated_at'),
            'after'  => Arr::except($this->getChanges(), 'updated_at')
        ] : null;
    }
}