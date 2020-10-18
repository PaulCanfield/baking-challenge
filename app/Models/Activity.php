<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [ ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function season() {
        return $this->belongsTo(Season::class);
    }

    public function subject() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
