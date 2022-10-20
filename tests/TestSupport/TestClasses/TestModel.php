<?php

namespace Spatie\ModelFlags\Tests\TestSupport\TestClasses;

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    public $guarded = [];
}
