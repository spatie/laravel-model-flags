# Add flags to Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-model-flags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-flags)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-model-flags/run-tests?label=tests)](https://github.com/spatie/laravel-model-flags/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-model-flags/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/spatie/laravel-model-flags/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-model-flags.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-model-flags)

This package offers a trait that allows you to add flags to an Eloquent model. 

```php
$user->hasFlag('myFlag'); // returns false;

$user->flag('myFlag');

$user->flag('flag-b'); // returns true;
```

It also provides scopes to quickly get all models with a certain flag.

```php
User::flagged('myFlag')->get(); // returns all models with the given flag
User::notFlagged('myFlag')->get(); // returns all models without the given flag
```

Though there are other usages, the primary use case of this package is to easily build idempotent (aka restartable) pieces of code. Image you should write an Artisan command that sends a mail to each user. Using flags, you can make sure that if the command is cancelled half-way, in the second invocation, you'll only send a mail to users that haven't received one yet. 

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

You can install the package via composer:

```bash
composer require spatie/laravel-model-flags
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-model-flags-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-model-flags-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-model-flags-views"
```

## Usage

```php
$modelFlags = new Spatie\ModelFlags();
echo $modelFlags->echoPhrase('Hello, Spatie!');
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
