<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use RecordActivity;

    protected $guarded = [ ];

    protected $observables = [
        'invited'
    ];

    public function path() {
        return "/season/{$this->id}";
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function bakers() {
        return $this->hasMany(Baker::class);
    }

    public function episodes() {
        return $this->hasMany(Episode::class);
    }

    public function addBaker($values) {
        return $this->bakers()->create($values);
    }

    public function addEpisode($values) {
        return $this->episodes()->create($values);
    }

    public function activities() {
        return $this->hasMany(Activity::class)->latest();
    }

    public function invite($user) {
        $member = $this->members()->attach($user);

        $user->fireModelEvent('invited');

        return $member;
    }

    public function members() {
        return $this->belongsToMany(User::class, 'season_members')->withTimestamps();
    }

    public function getUserId() {
        return $this->owner->id;
    }

    public function getSeasonId() {
        return $this->id;
    }
}
