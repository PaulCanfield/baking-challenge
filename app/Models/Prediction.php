<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $guarded = [ ];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function result() {
        return $this->belongsTo(Result::class);
    }

    public function baker() {
        return $this->belongsTo(Baker::class);
    }

    public function episode() {
        return $this->belongsTo(Episode::class);
    }
}
