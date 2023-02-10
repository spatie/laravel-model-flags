<p align="center"><img src="/art/socialcard.png" alt="Social Card of Laravel Permission"></p>

# Add flags to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-model-flags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-flags)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-model-flags/run-tests?label=tests)](https://github.com/spatie/laravel-model-flags/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-model-flags/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/spatie/laravel-model-flags/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-model-flags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-flags)

This package offers a trait that allows you to add flags to an Eloquent model. These can be used to quickly save the state of a process, update, migration, etc... to a model, without having to add an additional column using migrations.

```php
$user->hasFlag('receivedMail'); // returns false

$user->flag('receivedMail'); // flag the user as having received the mail

$user->hasFlag('receivedMail'); // returns true
```

It also provides scopes to quickly query all models with a certain flag.

```php
User::flagged('myFlag')->get(); // returns all models with the given flag
User::notFlagged('myFlag')->get(); // returns all models without the given flag
```

Though there are other usages, the primary use case of this package is to easily build idempotent (aka restartable) pieces of code. For example, when writing an Artisan command that sends a mail to each user. Using flags, you can make sure that when the command is cancelled (or fails) half-way through, in the second invocation, a mail will only be sent to users that haven't received one yet.

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

To create that `flags` table, you must publish and run the migrations once with:

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

To add flaggable behaviour to a model, simply make it use the `Spatie\ModelFlags\Models\Concerns\HasFlags` trait

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
// add a flag
$model->flag('myFlag');

// returns true if the model has a flag with the given name
$model->hasFlag('myFlag');

// remove a flag
$model->unflag('myFlag');

 // returns an array with the name of all flags on the model
$model->flagNames();

// use the `flags` relation to delete all flags on a model
$user->flags()->delete();

// use the `flags` relation to delete a particular flag on a model
$user->flags()->where('name', 'myFlag')->delete();
```

A flag can only exist once for a model. When flagging a model with the same flag again, the `updated_at` attribute of the flag will be updated.

```php
$model->flag('myFlag');

// after a while
$model->flag('myFlag'); // update_at will be updated
```

You can get the date of the last time a flag was used on a model.

```php
$model->lastFlaggedAt(); // returns the update time of the lastly updated flag
$model->lastFlaggedAt('myFlag') // returns the updated_at of the `myFlag` flag on the model
$model->lastFlaggedAt('doesNotExist') // returns null if there is no flag with the given name
```

You'll also get these scopes:

```php
// query all models that have a flag with the given name
YourModel::flagged('myFlag');

// query all models that have do not have a flag with the given name
YourModel::notFlagged('myFlag');
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

And a special thanks to [Caneco](https://twitter.com/caneco) for the logo ✨

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
