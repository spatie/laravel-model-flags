<?php

namespace Spatie\ModelFlags\Commands;

use Illuminate\Console\Command;

class ModelFlagsCommand extends Command
{
    public $signature = 'laravel-model-flags';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
