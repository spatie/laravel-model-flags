<?php

namespace Spatie\ModelFlags;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ModelFlagsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-model-flags')
            ->hasConfigFile()
            ->hasMigration('create_flags_table');
    }
}
