# Add flags to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-model-flags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-flags)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-model-flags/run-tests?label=tests)](https://github.com/spatie/laravel-model-flags/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-model-flags/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/spatie/laravel-model-flags/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-model-flags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-flags)

This package offers a trait that allows you to add flags to an Eloquent model. 

```php
$user->hasFlag('myFlag'); // returns false;

$user->flag('myFlag') // add a flag with the given name;

$user->flag('flag-b'); // returns true;
```

It also provides scopes to quickly get all models with a certain flag.

```php
User::flagged('myFlag')->get(); // returns all models with the given flag
User::notFlagged('myFlag')->get(); // returns all models without the given flag
```

Though there are other usages, the primary use case of this package is to easily build idempotent (aka restartable) pieces of code. Imagine you should write an Artisan command that sends a mail to each user. Using flags, you can make sure that if the command is cancelled half-way, in the second invocation, you'll only send a mail to users that haven't received one yet. 

```php
// in an Artisan command

User::notFlagged('wasSentPromotionMail')
    ->each(function(User $user) {
        Mail::to($user->email)->send(new PromotionMail())
       
        $user->flag('wasSentPromotionMail');
    });
});
```

No matter how many times you would execute this command, users would only get the mail once.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-model-flags.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-model-flags)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via Composer:

```bash
composer require spatie/laravel-model-flags
```

Behind the scenes, the flags and the relation to a model will be stored in the `flags` table. 

To create that `flags` table, you must publish and run the migrations with:

```bash
php artisan vendor:publish --tag="model-flags-migrations"
php artisan migrate
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag="model-flags-config"
```

This is the contents of the published config file:

```php
return [
    /*
     * The model used as the flag model.
     */
    'flag_model' => Spatie\ModelFlags\Models\Flag::class,
];
```

## Usage

To add flaggable behaviour to a model, simply let it use the `Spatie\ModelFlags\Models\Concerns\HasFlags` trait

```php
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelFlags\Models\Concerns\HasFlags;

class YourModel extends Model
{
    use HasFlags;
}
```

These functions will become available.

```php 
$model->flag('myFlag'); // add a flag
$model->hasFlag('myFlag'); // returns true if the model has a flag with the given name
$model->unflag('myFlag'); // remove a flag

$model->flagNames(); // returns an array with the name of all flags on the model

YourModel::flagged('myFlag'); // query all models that have a flag with the given name
YourModel::notFlagged('myFlag'); // query all models that have do not have a flag with the given name
```

To remove a flag from all models in one go, you can delete the flag using the `Spatie\ModelFlags\Models\Flag` model.

```php
use Spatie\ModelFlags\Models\Flag;

// remove myFlag from all models
Flag::where('name', 'myFlag')->delete();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
