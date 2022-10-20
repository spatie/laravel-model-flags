<?php

namespace Spatie\ModelFlags\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\ModelFlags\ModelFlags
 */
class ModelFlags extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Spatie\ModelFlags\ModelFlags::class;
    }
}
