<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\RecordActivity;

class User extends Authenticatable
{
    use Notifiable, RecordActivity, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function seasons() {
       return $this->hasMany(Season::class, 'owner_id')->latest('updated_at');
    }

    public function joinedSeasons() {
        return Season::where('owner_id', $this->id)
            ->orWhereHas('members', function ($query) {
                $query->where('user_id', $this->id);
            })
            ->latest()
            ->get();
    }

    protected static function dispatchedEvents() {
        return [
            'invited'
        ];
    }

    public static function invited($callback) {
        static::registerModelEvent('invited', $callback);
    }

    public function getUserId() {
        return $this->joinedSeasons()->last()->owner->id;
    }

    public function getSeasonId() {
        return $this->joinedSeasons()->last()->id;
    }

    public function completePredictions($episode) {

    }
}
