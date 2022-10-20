<?php

namespace Spatie\ModelFlags;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\ModelFlags\Commands\ModelFlagsCommand;

class ModelFlagsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-model-flags')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-model-flags_table')
            ->hasCommand(ModelFlagsCommand::class);
    }
}
