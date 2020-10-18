<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonObject extends Model
{
    use HasFactory;

    protected $guarded = [ ];

    protected $touches = [ 'season' ];

    public function season() {
        return $this->belongsTo(Season::class);
    }

    public function getUserId() {
        return $this->season->owner->id;
    }

    public function apply(Builder $builder, Model $model) {
        parent::apply($builder, $model);
    }
}
