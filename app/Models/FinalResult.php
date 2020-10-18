<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalResult extends Model
{
    use HasFactory;

    protected $guarded = [ ];

    public function season() {
        return $this->belongsTo(Season::class);
    }

    public function baker() {
        return $this->belongsTo(Baker::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
