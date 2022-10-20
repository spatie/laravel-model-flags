<?php

namespace Spatie\ModelFlags\Tests\TestSupport\TestClasses;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class TestModel extends Model
{
    use HasFlags;

    public $guarded = [];
}
