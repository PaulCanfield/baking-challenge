<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $guarded = [ ];

    public function __toString() {
        return $this->result;
    }
}
