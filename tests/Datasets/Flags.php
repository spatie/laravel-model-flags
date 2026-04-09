<?php

use Spatie\ModelFlags\Tests\TestSupport\TestBackedEnum;

dataset('flags', [
    'a string' => 'flag-a',
    'a backed enum' => TestBackedEnum::test_case,
]);

dataset('flag array', [
    fn () => ['flag-a', TestBackedEnum::test_case],
]);
