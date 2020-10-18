<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodeResult extends Model
{
    use HasFactory;

    protected $guarded = [ ];

    public function result() {
        return $this->belongsTo(Result::class);
    }

    public function baker() {
        return $this->belongsTo(Baker::class);
    }
}
