# Socialite Providers "Login with Amazon" Extention Compatible with SocialStream for Jetsream

https://login.amazon.com/

![hero](https://neonos.s3.amazonaws.com/Socialite+Amazon_AWS+Login.png)



## Requirements

-   PHP >= 7.2

(& One of the below packages:) in order of prefrence for easy plug and play...
-   [joelbutcher/socialstream](https://github.com/joelbutcher/socialstream) Documentation: [Here](https://docs.socialstream.dev/)
-   [socialiteproviders/manager](https://github.com/SocialiteProviders/Providers) Documentation: [Here](https://socialiteproviders.com/)
-   [laravel/socialite](https://github.com/laravel/socialite) Documentation: [Here](https://laravel.com/docs/9.x/socialite)

If you are looking to implement Login with Amazon into Laravel and you are unsure of which requirement to choose, then if you are using JetStream we recommend social stream. Otherwise with breeze or custom applications use Social Providers.


## Installation For Socialite Providers & Basic Usage (Package: SocialiteProviders/Providers)

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

## Installation without additional packages (No Social Stream/Social Providers)

Please see "Socialite Standalone Usage" below after following the rest of this installation.

## Installation

```bash
composer require n30/socialiteproviders-amazon
```
The package for flexibility of proficient socialite developers who wish to use their own custom methods, does not require additional packages, if you are not sure make sure to next run the commands, otherwise the package will not work and following the instruction will through an error, so...

## Next run:

```bash
composer require socialiteproviders/manager
```

### Add configuration to `config/services.php`

```
    'amazon' => [
        'client_id'     => env('AMAZON_CLIENT_ID'),
        'client_secret' => env('AMAZON_CLIENT_SECRET'),
        'redirect'      => env('AMAZON_REDIRECT_URI'), //recommended: /oauth/amazon/callback
    ],
``` 

### Enable Socialite Packag as SocialiteProviders Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \SocialiteProviders\Amazon\AmazonExtendSocialite::class.'@handle',
    ],
];
```

### .env

```
AMAZON_CLIENT_ID=amzn1.application-oa2-client.xxxxxxxxxxxxxxxxxxxx
AMAZON_CLIENT_SECRET=xxxxxxxxxxxxxxxxxxxxxxxx
AMAZON_REDIRECT_URI="${APP_URL}/oauth/amazon/callback"
```

### Enable for Social Stream
If using on Social Stream, edit your config/socialstream.php file to add:
```
    'providers' => [
        Providers::google(),
        Providers::facebook(),
        Providers::twitter(),
        Providers::linkedin(),
        'amazon' //<--Add Amazon at the end of your list of providers

    ],
```


## Socialite Standalone Usage

You can skip this part if you are using Social Stream or the socialite providers base package.

routes/web.php

```
Route::get('/', 'AmazonController@index');
Route::get('callback', 'AmazonController@callback');
```

Create controller in desired location and name, example:
```
php artisan make:controll AmazonController
```

AmazonController

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AmazonController extends Controller
{
    public function index()
    {
        return Socialite::driver('amazon')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('amazon')->user();
        dd($user);
    }
}

```

## Amazon API Key

And last you will need an Amazon API key:

[Amazon Login Documentaiton](https://developer.amazon.com/docs/login-with-amazon/register-other-platforms-cbl-docs.html#create-a-new-security-profile) explains all the details for creating a security profile and then enabling Amazon Login for it.

Create API keys [here]( https://developer.amazon.com/loginwithamazon/console/site/lwa/overview.html)


### Usage  

### Social Stream Usage

For more design integrations please see Official Documentation of Social Stream for [Socialite Providers](https://docs.socialstream.dev/getting-started/socialite-providers) based on your Livewire or Intertia implementation. An example of socialstream.blade.php button would be:

```
    <a href="{{ route('oauth.redirect', ['provider' => 'amazon' ]) }}">
        {{-- Place your Amazon SVG Icon here... I recommend Blade-ui-icons backage for easy icons: @svg('jam-amazon', "h-6 w-6") --}}
        <span class="sr-only">Amazon</span>
    </a>
```

### Socialite Standalone & Socialite Provider

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('amazon')->redirect();
```

### Returned User fields

- ``name``
- ``email``
- ``user_id``

See Amazon [Documentation](https://developer.amazon.com/docs/login-with-amazon/customer-profile.html) for details.


## LICENCE

MIT
Copyright (c) 2023 n30
