<?php

namespace App;

class Baker extends SeasonObject
{
    use RecordActivity;

    public function path() {
        return '/baker/'. $this->id;
    }
}
