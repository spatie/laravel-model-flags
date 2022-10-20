<?php

namespace Spatie\ModelFlags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Flag extends Model
{
    public function flaggable(): MorphTo
    {
        return $this->morphTo();
    }
}
